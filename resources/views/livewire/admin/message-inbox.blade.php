<section class="mx-auto w-full max-w-6xl space-y-6">
    <div>
        <div class="font-mono text-xs font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
            04 // {{ __('Inbox') }}
        </div>
        <flux:heading size="xl" class="mt-0.5">{{ __('Messages') }}</flux:heading>
        <flux:text class="mt-1">
            {{ __('Review and manage inquiries submitted from your public portfolio contact form.') }}
        </flux:text>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Total Messages') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-zinc-500/10 text-zinc-600 dark:text-zinc-300">
                    <flux:icon name="inbox" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->totalCount }}
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Unread Messages') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                    <flux:icon name="envelope" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->unreadCount }}
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Read Messages') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-700 dark:text-cyan-400">
                    <flux:icon name="envelope-open" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->totalCount - $this->unreadCount }}
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
        @if ($this->messages->isEmpty())
            <div class="p-14 text-center">
                <flux:heading size="lg">{{ __('Inbox is empty') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Messages submitted through your contact form will appear here.') }}
                </flux:text>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-left text-sm dark:divide-zinc-700/80">
                    <thead class="bg-zinc-50/80 text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <tr>
                            <th class="px-5 py-3.5">{{ __('Sender') }}</th>
                            <th class="px-5 py-3.5">{{ __('Subject & Message') }}</th>
                            <th class="px-5 py-3.5">{{ __('Received') }}</th>
                            <th class="px-5 py-3.5">{{ __('Status') }}</th>
                            <th class="px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach ($this->messages as $msg)
                            <tr
                                wire:key="message-{{ $msg->id }}"
                                @class([
                                    'align-top transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30',
                                    'bg-blue-50/40 dark:bg-blue-950/15' => ! $msg->is_read,
                                ])
                            >
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $msg->name }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $msg->email }}
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $msg->subject ?: __('(No Subject)') }}
                                    </div>
                                    <p class="mt-1 line-clamp-2 max-w-md text-xs text-zinc-600 dark:text-zinc-400">
                                        {{ $msg->message }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $msg->created_at?->diffForHumans() }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <button
                                        type="button"
                                        wire:click="toggleRead({{ $msg->id }})"
                                        class="cursor-pointer"
                                    >
                                        <flux:badge :color="$msg->is_read ? 'zinc' : 'blue'" size="sm">
                                            {{ $msg->is_read ? __('Read') : __('Unread') }}
                                        </flux:badge>
                                    </button>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50/70 p-1 dark:border-zinc-700 dark:bg-zinc-800/50">
                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="ghost"
                                            icon="eye"
                                            wire:click="viewMessage({{ $msg->id }})"
                                            title="{{ __('Read') }}"
                                            aria-label="{{ __('Read') }}"
                                        />

                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="ghost"
                                            :icon="$msg->is_read ? 'envelope' : 'envelope-open'"
                                            wire:click="toggleRead({{ $msg->id }})"
                                            :title="$msg->is_read ? __('Mark Unread') : __('Mark Read')"
                                            :aria-label="$msg->is_read ? __('Mark Unread') : __('Mark Read')"
                                        />

                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="danger"
                                            icon="trash"
                                            wire:click="confirmDelete({{ $msg->id }})"
                                            title="{{ __('Delete') }}"
                                            aria-label="{{ __('Delete') }}"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <flux:modal wire:model="showViewModal" class="max-w-xl">
        <div class="space-y-4">
            <div class="space-y-1.5 pe-8">
                <div class="flex flex-wrap items-center gap-2.5">
                    <flux:heading size="lg">
                        {{ $viewingSubject ?: __('(No Subject)') }}
                    </flux:heading>
                    <flux:badge :color="$viewingIsRead ? 'zinc' : 'blue'" size="sm">
                        {{ $viewingIsRead ? __('Read') : __('Unread') }}
                    </flux:badge>
                </div>
                <flux:text class="text-sm">
                    {{ __('From:') }} {{ $viewingName }} ({{ $viewingEmail }})
                </flux:text>
                @if ($viewingDate)
                    <flux:text class="text-xs text-zinc-500">
                        {{ $viewingDate }}
                    </flux:text>
                @endif
            </div>

            <flux:separator />

            <div class="whitespace-pre-line rounded-lg bg-zinc-50 p-4 text-sm leading-relaxed text-zinc-800 dark:bg-zinc-800/60 dark:text-zinc-200">
                {{ $viewingBody }}
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                @if ($viewingId)
                    <flux:button type="button" variant="outline" wire:click="toggleRead({{ $viewingId }})">
                        {{ $viewingIsRead ? __('Mark as Unread') : __('Mark as Read') }}
                    </flux:button>
                @endif

                <div class="flex items-center gap-2">
                    <flux:button type="button" variant="ghost" wire:click="$set('showViewModal', false)">
                        {{ __('Close') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete Message') }}</flux:heading>
            <flux:text>
                {{ __('Are you sure you want to delete the message from ":name"? This action cannot be undone.', ['name' => $deletingName]) }}
            </flux:text>

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showDeleteModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="button" variant="danger" wire:click="delete">
                    {{ __('Delete Message') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section>
