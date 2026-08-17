<?php

namespace App\Services;

use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProjectDetailExtractor
{
    /**
     * Parse project metadata from a file.
     */
    public static function extract(string $filePath, string $originalName): array
    {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $text = '';

        try {
            if ($ext === 'json') {
                $jsonData = json_decode(file_get_contents($filePath), true);
                if (is_array($jsonData)) {
                    return self::normalizeJsonData($jsonData);
                }
            } elseif ($ext === 'txt') {
                $text = file_get_contents($filePath);
            } elseif (in_array($ext, ['docx', 'doc'])) {
                $text = self::extractDocxText($filePath);
            } elseif ($ext === 'pdf') {
                $text = self::extractPdfText($filePath);
            }
        } catch (\Throwable $e) {
            Log::error('ProjectDetailExtractor: Error reading file ' . $originalName . ' - ' . $e->getMessage());
        }

        // Clean raw text to be valid UTF-8
        $text = self::sanitizeUtf8($text);

        return self::parseFromText($text);
    }

    /**
     * Normalize pre-structured JSON file content.
     */
    private static function normalizeJsonData(array $data): array
    {
        $resolved = [
            'name' => self::sanitizeUtf8($data['name'] ?? $data['title'] ?? ''),
            'description' => self::sanitizeUtf8($data['description'] ?? $data['summary'] ?? ''),
            'start_date' => null,
            'deadline' => null,
            'subsidiary_id' => null,
            'project_manager_id' => null,
            'participant_ids' => [],
            'raw_text' => '',
        ];

        // Safely encode JSON to raw text
        $encodedRaw = json_encode($data, JSON_PRETTY_PRINT);
        $resolved['raw_text'] = self::sanitizeUtf8($encodedRaw ?: '');

        // Parse dates
        if (!empty($data['start_date'])) {
            $ts = strtotime($data['start_date']);
            if ($ts) $resolved['start_date'] = date('Y-m-d', $ts);
        }
        if (!empty($data['deadline']) || !empty($data['end_date'])) {
            $ts = strtotime($data['deadline'] ?? $data['end_date']);
            if ($ts) $resolved['deadline'] = date('Y-m-d', $ts);
        }

        // Resolve Subsidiary
        $subQuery = self::sanitizeUtf8($data['subsidiary'] ?? $data['company'] ?? '');
        if ($subQuery) {
            $sub = Subsidiary::where('name', 'like', "%{$subQuery}%")
                ->orWhere('code', 'like', "%{$subQuery}%")
                ->first();
            if ($sub) $resolved['subsidiary_id'] = $sub->id;
        }

        // Resolve PM
        $pmQuery = self::sanitizeUtf8($data['pm'] ?? $data['project_manager'] ?? $data['owner'] ?? '');
        if ($pmQuery) {
            $pm = User::where('email', $pmQuery)
                ->orWhere('name', 'like', "%{$pmQuery}%")
                ->first();
            if ($pm) $resolved['project_manager_id'] = $pm->id;
        }

        // Resolve Participants
        $participants = $data['participants'] ?? $data['members'] ?? $data['team'] ?? [];
        if (is_string($participants)) {
            $participants = array_filter(array_map('trim', explode(',', $participants)));
        }
        if (is_array($participants)) {
            foreach ($participants as $pQuery) {
                $pQueryClean = self::sanitizeUtf8((string)$pQuery);
                if (empty($pQueryClean)) continue;

                $user = User::where('email', $pQueryClean)
                    ->orWhere('name', 'like', "%{$pQueryClean}%")
                    ->first();
                if ($user) {
                    $resolved['participant_ids'][] = $user->id;
                }
            }
        }

        return $resolved;
    }

    /**
     * Extract text content from docx.
     */
    private static function extractDocxText(string $filePath): string
    {
        if (!class_exists('ZipArchive')) return '';
        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                $xml = str_replace(['</w:p>', '</w:tr>', '<w:br/>'], ["\n\n", "\n", "\n"], $data);
                return trim(html_entity_decode(strip_tags($xml)));
            }
            $zip->close();
        }
        return '';
    }

    /**
     * Pure PHP PDF text extractor.
     */
    private static function extractPdfText(string $filePath): string
    {
        if (!file_exists($filePath)) return '';
        $content = file_get_contents($filePath);
        if (!$content) return '';

        preg_match_all("/\bstream\b(.*?)\bendstream\b/ms", $content, $matches);
        $text = '';
        foreach ($matches[1] as $stream) {
            $streamData = trim($stream);
            $uncompressed = '';
            try {
                $uncompressed = @gzuncompress($streamData);
            } catch (\Exception $e) {}

            if (empty($uncompressed)) {
                try {
                    $uncompressed = @gzdecode($streamData);
                } catch (\Exception $e) {}
            }

            if (empty($uncompressed)) {
                $uncompressed = $streamData;
            }

            // Extract Tj and TJ tags
            preg_match_all("/\((.*?)\)\s*(Tj|TJ)/", $uncompressed, $textMatches);
            foreach ($textMatches[1] as $tMatch) {
                $text .= $tMatch . ' ';
            }

            preg_match_all("/\[(.*?)\]\s*TJ/ms", $uncompressed, $tjMatches);
            foreach ($tjMatches[1] as $tjMatch) {
                preg_match_all("/\((.*?)\)/", $tjMatch, $subMatches);
                foreach ($subMatches[1] as $subMatch) {
                    $text .= $subMatch;
                }
                $text .= ' ';
            }
        }

        $text = preg_replace("/\\\\([0-9]{3})/", "", $text);
        $text = str_replace(["\\(", "\\)", "\\\\"], ["(", ")", "\\"], $text);
        return trim($text);
    }

    /**
     * Helper to retrieve only the first line of a match and limit length.
     */
    private static function getFirstLine(string $str, int $limit = 120): string
    {
        $lines = preg_split("/\r\n|\n|\r/", $str);
        $firstLine = trim($lines[0] ?? '');
        return mb_substr($firstLine, 0, $limit);
    }

    /**
     * Helper to sanitize string to be valid UTF-8 and strip replacement characters.
     */
    private static function sanitizeUtf8(string $str): string
    {
        // Convert to UTF-8 and ignore/remove invalid sequences
        $clean = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        // Remove unicode replacement characters
        $clean = str_replace("\xEF\xBF\xBD", '', $clean);
        return trim($clean);
    }

    /**
     * Regex and fuzzy parser from unstructured text.
     */
    private static function parseFromText(string $text): array
    {
        $resolved = [
            'name' => '',
            'description' => '',
            'start_date' => null,
            'deadline' => null,
            'subsidiary_id' => null,
            'project_manager_id' => null,
            'participant_ids' => [],
            'raw_text' => $text,
        ];

        if (empty(trim($text))) {
            return $resolved;
        }

        // --- SECTION 1: KEYWORD REGEXES ---

        // 1. Project Name
        if (preg_match("/\b(?:project\s*name|name\s*of\s*project|title|project\s*title)\b\s*[:=-]\s*(.+)/i", $text, $matches)) {
            $resolved['name'] = self::getFirstLine($matches[1], 150);
        }

        // 2. Description
        if (preg_match("/\b(?:description|summary|project\s*summary|about)\b\s*[:=-]\s*(.+)/i", $text, $matches)) {
            $resolved['description'] = mb_substr(trim($matches[1]), 0, 1000);
        }

        // 3. Start Date
        if (preg_match("/\b(?:start\s*date|kickoff\s*date|commencement)\b\s*[:=-]\s*([A-Za-z0-9\s,\-\/.]+)/i", $text, $matches)) {
            $dateStr = self::getFirstLine($matches[1], 50);
            $ts = strtotime($dateStr);
            if ($ts) $resolved['start_date'] = date('Y-m-d', $ts);
        }

        // 4. Deadline / End Date
        if (preg_match("/\b(?:deadline|end\s*date|completion\s*date|due\s*date|target\s*date)\b\s*[:=-]\s*([A-Za-z0-9\s,\-\/.]+)/i", $text, $matches)) {
            $dateStr = self::getFirstLine($matches[1], 50);
            $ts = strtotime($dateStr);
            if ($ts) $resolved['deadline'] = date('Y-m-d', $ts);
        }

        // 5. Resolve Subsidiary
        if (preg_match("/\b(?:subsidiary|division|department|company|org)\b\s*[:=-]\s*(.+)/i", $text, $matches)) {
            $subQuery = self::getFirstLine($matches[1], 100);
            if (!empty($subQuery)) {
                $sub = Subsidiary::where('name', 'like', "%{$subQuery}%")
                    ->orWhere('code', 'like', "%{$subQuery}%")
                    ->first();
                if ($sub) $resolved['subsidiary_id'] = $sub->id;
            }
        }

        // 6. Resolve PM
        if (preg_match("/\b(?:project\s*manager|pm|lead|owner)\b\s*[:=-]\s*(.+)/i", $text, $matches)) {
            $pmQuery = self::getFirstLine($matches[1], 100);
            if (!empty($pmQuery)) {
                if (preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $pmQuery, $emailMatch)) {
                    $pm = User::where('email', $emailMatch[0])->first();
                } else {
                    $pm = User::where('name', 'like', "%{$pmQuery}%")->first();
                }
                if ($pm) $resolved['project_manager_id'] = $pm->id;
            }
        }

        // 7. Resolve Participants
        if (preg_match("/\b(?:participants|members|team|collaborators)\b\s*[:=-]\s*(.+)/i", $text, $matches)) {
            $teamQuery = self::getFirstLine($matches[1], 500);
            $candidates = array_filter(array_map('trim', preg_split("/[,;]/", $teamQuery)));
            foreach ($candidates as $candidate) {
                if (empty($candidate)) continue;
                
                if (preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $candidate, $emailMatch)) {
                    $u = User::where('email', $emailMatch[0])->first();
                } else {
                    $u = User::where('name', 'like', "%{$candidate}%")->first();
                }
                if ($u) {
                    $resolved['participant_ids'][] = $u->id;
                }
            }
        }

        // --- SECTION 2: INTELLIGENT FUZZY FALLBACKS ---

        // A. Fuzzy Subsidiary Lookup: Search text body for any known subsidiary name or code
        if (!$resolved['subsidiary_id']) {
            $subsidiaries = Subsidiary::all();
            foreach ($subsidiaries as $sub) {
                if (stripos($text, $sub->name) !== false || (!empty($sub->code) && preg_match("/\b" . preg_quote($sub->code, '/') . "\b/i", $text))) {
                    $resolved['subsidiary_id'] = $sub->id;
                    break;
                }
            }
        }

        // B. Fuzzy Email Scanner: Match registered active users and auto-assign PM & Members
        preg_match_all("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $text, $emailMatches);
        $foundEmails = array_unique($emailMatches[0] ?? []);
        foreach ($foundEmails as $email) {
            $u = User::where('email', $email)->first();
            if ($u) {
                // Determine PM assignment
                if (!$resolved['project_manager_id'] && ($u->hasRole('project_manager') || $u->hasRole('super_admin') || stripos($text, 'lead') !== false || stripos($text, 'pm') !== false)) {
                    $resolved['project_manager_id'] = $u->id;
                } else {
                    if (!in_array($u->id, $resolved['participant_ids'])) {
                        $resolved['participant_ids'][] = $u->id;
                    }
                }
            }
        }

        // C. Fuzzy Project Name: Fallback to the first line if no pattern matched
        if (empty($resolved['name'])) {
            $firstLine = self::getFirstLine($text, 150);
            if (!empty($firstLine)) {
                if (strpos($firstLine, '|') !== false) {
                    $parts = explode('|', $firstLine);
                    $resolved['name'] = trim($parts[0]);
                } elseif (strpos($firstLine, ' - ') !== false) {
                    $parts = explode(' - ', $firstLine);
                    $resolved['name'] = trim($parts[0]);
                } else {
                    $resolved['name'] = $firstLine;
                }
            }
        }

        // D. Fuzzy Description: Look for blocks inside quotes
        if (empty($resolved['description'])) {
            if (preg_match("/\"([^\"]{10,500})\"/", $text, $matches)) {
                $resolved['description'] = trim($matches[1]);
            }
        }

        // E. Fuzzy Date Scanner: Scan for timeline dates (like "Jul 31, 2026")
        preg_match_all("/\b(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\s+\d{1,2},?\s+\d{4}\b/i", $text, $dateMatches);
        $foundDates = $dateMatches[0] ?? [];
        if (count($foundDates) >= 2) {
            $tsStart = strtotime($foundDates[0]);
            $tsEnd = strtotime($foundDates[1]);
            if ($tsStart && !$resolved['start_date']) {
                $resolved['start_date'] = date('Y-m-d', $tsStart);
            }
            if ($tsEnd && !$resolved['deadline']) {
                $resolved['deadline'] = date('Y-m-d', $tsEnd);
            }
        }

        return $resolved;
    }
}
