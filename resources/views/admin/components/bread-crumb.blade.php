<section>
    <div class="w-full py-4 px-4  bg-gray-200">
        <ul class="flex flex-row space-x-2">
            @foreach ($breadCrumbs as $index => $breadCrumb)
                <li><a href="{{ $breadCrumb['url'] }}"
                        class="capitalize text-black/60 text-sm cursor-text @if ($index != count($breadCrumbs) - 1) !cursor-pointer !text-base !text-indigo-600 @endif">{{ $breadCrumb['name'] }}</a>
                </li>
                @if ($index != count($breadCrumbs) - 1)
                    <li><a class="text-gray-500">/</a></li>
                @endif
            @endForeach
        <ul>
    </div>
</section>
