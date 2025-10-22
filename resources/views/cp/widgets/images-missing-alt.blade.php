<ui-widget
    title="{{ __('statamic-peak-tools::default.widget_assets_title', ['container' => $containers[0]]) }}"
    icon="assets"
>
    <div class="px-4 py-3 flex flex-col gap-4 text-sm">
        <p>
            {{ __('statamic-peak-tools::default.widget_assets_explanation') }}
            {{ trans_choice('statamic-peak-tools::default.widget_assets_count', $amount, ['amount' => $amount]) }}
        </p>

        @if ($assets)
            <table class="w-full">
                <tbody>
                @endif
                @forelse ($assets as $asset)
                    <tr>
                        <td class="px-0.75 py-0.5 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-red-500"></span>
                                </span>

                                <a href="{{ $asset['edit_url'] }}" aria-label="{{ __('statamic-peak-tools::default.widget_assets_edit') }}">{{ $asset['basename'] }}</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>{{ __('statamic-peak-tools::default.widget_assets_done') }}</p>
                @endforelse
                @if ($assets)
                </tbody>
            </table>
        @endif
    </div>
</ui-widget>
