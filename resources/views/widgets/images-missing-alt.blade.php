<div class="card p-0 overflow-hidden h-full">
    <div class="flex justify-between items-center p-4 dark:bg-dark-650 dark:border-b dark:border-dark-900">
        <h2>
            <div class="flex items-center">
                <div class="h-6 w-6 mr-2 text-gray-800 dark:text-dark-200">
                    @cp_svg('icons/light/assets')
                </div>
                <span>{{ __('strings.widget_assets_title', ['container' => $containers[0]]) }}</span>
            </div>
        </h2>
    </div>
    <div class="content px-4 py-4">
        <p>
            {{ __('strings.widget_assets_explanation') }}
            {{ trans_choice('strings.widget_assets_count', $amount, ['amount' => $amount]) }}
        </p>
    </div>

    @if ($assets)
        <table tabindex="0" class="data-table">
            <tbody tabindex="0">
    @endif
    @forelse ($assets as $asset)
        <tr class="sortable-row outline-none" tabindex="0">
            <td>
                <div class="flex items-center">
                    <div class="little-dot mr-2 bg-red-500"></div>
                    <a href="{{ $asset['edit_url'] }}" aria-label="{{ __('strings.widget_assets_edit') }}">{{ $asset['basename'] }}</a>
                </div>
            </td>
            <td class="actions-column"></td>
        </tr>
    @empty
        <div class="content p-4">
            <p>{{ __('strings.widget_assets_done') }}</p>
        </div>
    @endforelse
    @if ($assets)
            </tbody>
        </table>
    @endif
</div>
