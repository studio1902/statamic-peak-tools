<div class="bg-white dark:bg-gray-800 rounded-xl ring ring-gray-200 dark:ring-x-0 dark:ring-b-0 dark:ring-gray-700 shadow-ui-md @container/widget h-full">
    <header class="flex items-center min-h-[49px] justify-between border-b border-gray-200 px-4.5 py-2 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <svg class=" shrink-0 hidden! size-5 text-gray-500 @xs/widget:block! hidden!" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g transform="translate(1 1)" stroke="currentColor" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M0.239848,10.8782857 C0.416,12.5248 1.74022857,13.8490286 3.38589714,14.0324571 C4.60824,14.1686857 5.86328,14.2857143 7.14285714,14.2857143 C8.42243429,14.2857143 9.67747429,14.1686857 10.8997714,14.0324571 C12.5454857,13.8490286 13.8697143,12.5248 14.0458286,10.8782857 C14.1758857,9.66288 14.2857143,8.41502857 14.2857143,7.14285714 C14.2857143,5.87067429 14.1758857,4.62283429 14.0458286,3.40739429 C13.8697143,1.76093714 12.5454857,0.436697143 10.8997714,0.253267429 C9.67747429,0.117019429 8.42243429,0 7.14285714,0 C5.86328,0 4.60824,0.117019429 3.38589714,0.253267429 C1.74022857,0.436697143 0.416,1.76093714 0.239848,3.40739429 C0.109809143,4.62283429 0,5.87067429 0,7.14285714 C0,8.41502857 0.109810286,9.66288 0.239848,10.8782857 L0.239848,10.8782857 Z" />
                    <path d="M9.64062857,5.84152 C10.6642286,5.84152 11.24,5.26576 11.24,4.24218286 C11.24,3.21861714 10.6642286,2.64285714 9.64062857,2.64285714 C8.61705143,2.64285714 8.04129143,3.21861714 8.04129143,4.24218286 C8.04129143,5.26576 8.61705143,5.84152 9.64062857,5.84152 L9.64062857,5.84152 Z" />
                    <path d="M3.38602286,14.0324571 C1.74036571,13.8490286 0.416137143,12.5248 0.239980571,10.8782857 C0.187091429,10.3839657 0.137547429,9.88425143 0.0973771429,9.37972571 C1.07053714,8.11075429 2.23736,7.14285714 3.51715429,7.14285714 C6.36521143,7.14285714 8.65374857,11.9362286 9.49368,14.1739429 C8.72085714,14.2406857 7.93664,14.2857143 7.14299429,14.2857143 C5.86340571,14.2857143 4.60836571,14.1686857 3.38602286,14.0324571 L3.38602286,14.0324571 Z" />
                    <path d="M13.8370286,11.7500571 C12.7993143,10.2546171 11.5054857,9.03571429 10.06928,9.03571429 C9.16346286,9.03571429 8.31424,9.52059429 7.55022857,10.2539086 C8.43904,11.6305143 9.11417143,13.1630857 9.49362286,14.1739429 C9.9668,14.1331429 10.4357143,14.0842286 10.8998857,14.0324571 C12.2425143,13.8827429 13.3712,12.9738286 13.8370286,11.7500571 L13.8370286,11.7500571 Z" />
                </g>
            </svg>

            <span>
                {{ __('statamic-peak-tools::default.widget_assets_title', ['container' => $containers[0]]) }}
            </span>
        </div>
    </header>


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
                            <td class="p-0.75 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="flex items-center gap-2">
                                        <span class="size-2 rounded-full peak:bg-red-500"></span>
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
</div>
