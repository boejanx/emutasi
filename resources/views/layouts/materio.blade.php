<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- Materio minimal: use Tailwind + simple styles for this example -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
      /* small tweaks to match Materio feel */
      .card{background:#fff;border-radius:.5rem;box-shadow:0 6px 18px rgba(0,0,0,.08)}
      .brand{color:#f43;font-weight:600}
    </style>
  </head>
  <body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl mx-auto p-6">
      <header class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded bg-red-400 flex items-center justify-center text-white font-bold">M</div>
          <div>
            <div class="brand">{{ config('app.name') }}</div>
            <div class="text-sm text-gray-500">Materio - Minimal</div>
          </div>
        </div>
        <nav>
          @auth
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-red-600">Logout</button></form>
          @else
            <a href="{{ route('login') }}" class="text-sm text-gray-700 mr-4">Login</a>
            <a href="{{ route('register') }}" class="text-sm text-gray-700">Register</a>
          @endauth
        </nav>
      </header>

      <main>
        {{ $slot ?? \Illuminate\Support\Arr::get(get_defined_vars(), 'content') }}
      </main>
    </div>
  </body>
</html>
