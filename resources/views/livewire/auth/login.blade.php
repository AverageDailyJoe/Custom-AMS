<div class="max-w-md mx-auto mt-16 p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Admin Login</h1>

    @if ($message)
        <div class="mb-4 text-sm text-red-600">{{ $message }}</div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" wire:model="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Sign in</button>
    </form>
</div>
