@props(['answersCount' => 4])

<div class="mb-4 p-3 bg-muted/50 rounded-lg border max-w-3xl m-auto" >
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-muted-foreground">
            @svg('lucide-keyboard', 'w-4 h-4')
            <span>
        Use keyboard: Press <kbd class="px-2 py-1 text-xs bg-background border rounded">1-{{ $answersCount }}</kbd> to select answers,
        <kbd class="px-2 py-1 text-xs bg-background border rounded">Enter</kbd> to continue
      </span>
        </div>
        <button type="button" class="text-muted-foreground hover:text-foreground transition-colors" wire:click="hideKeyboardHelp">
            @svg('lucide-x', 'w-4 h-4')
        </button>
    </div>
</div>
