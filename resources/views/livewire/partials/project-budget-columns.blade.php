<td class="align-middle whitespace-nowrap">
    @if($showProjectBudget && $budgetProject->estimated_budget !== null)
        <div class="space-y-0.5">
            <span class="block text-xs font-bold text-slate-800 font-mono">
                Rs. {{ number_format((float) $budgetProject->estimated_budget, 0) }}
            </span>
            <span class="block text-[10px] font-medium text-slate-400">Allocated</span>
        </div>
    @else
        <span class="text-xs font-semibold text-slate-400 font-mono">&mdash;</span>
    @endif
</td>
<td class="align-middle whitespace-nowrap">
    @if($showProjectBudget && $budgetProject->actual_cost !== null)
        <div class="space-y-0.5">
            <span class="block text-xs font-bold font-mono {{ $budgetProject->estimated_budget !== null && (float) $budgetProject->actual_cost > (float) $budgetProject->estimated_budget ? 'text-rose-600' : 'text-slate-800' }}">
                Rs. {{ number_format((float) $budgetProject->actual_cost, 0) }}
            </span>
            <span class="block text-[10px] font-medium text-slate-400">Spent</span>
        </div>
    @else
        <span class="text-xs font-semibold text-slate-400 font-mono">&mdash;</span>
    @endif
</td>
