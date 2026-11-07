<x-filament-panels::page>
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <table class="w-full table-auto divide-y divide-gray-200 text-sm dark:divide-white/10">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Key</th>
                    <th class="px-4 py-3 text-left font-medium">Name</th>
                    <th class="px-4 py-3 text-left font-medium">Description</th>
                    <th class="px-4 py-3 text-left font-medium">Enabled</th>
                    <th class="px-4 py-3 text-right font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                @foreach ($this->getModules() as $module)
                    @php($enabled = $this->isModuleEnabled($module['key']))
                    <tr wire:key="module-{{ $module['key'] }}">
                        <td class="px-4 py-3 font-mono text-xs">{{ $module['key'] }}</td>
                        <td class="px-4 py-3">{{ $module['name'] }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $module['description'] }}</td>
                        <td class="px-4 py-3">
                            <x-filament::badge :color="$enabled ? 'success' : 'gray'">
                                {{ $enabled ? 'Yes' : 'No' }}
                            </x-filament::badge>
                        </td>
                        <td class="space-x-2 px-4 py-3 text-right">
                            @if (! $enabled)
                                <x-filament::button size="sm" wire:click="enableModule('{{ $module['key'] }}')">
                                    Enable
                                </x-filament::button>
                            @elseif (empty($module['core']))
                                <x-filament::button size="sm" color="danger" wire:click="disableModule('{{ $module['key'] }}')">
                                    Disable
                                </x-filament::button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
