<header class="h-16 fixed z-20 flex py-2 px-4 items-center justify-between bg-white border-1 shadow-lg w-full">
  <img class="w-28 h-10" src="{{ asset('logo.jpeg') }}"></img>
  <button id="profileButton" class="focus:outline-none">
      <i class="fa-regular fa-user"></i>
  </button>
</header>

<div id="profileDropdown" class="hidden absolute right-4 top-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
  <a href="{{ route('admin.profile.change-password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">{{ __('messages.header.change_password') }}</a>
  <a onclick="event.preventDefault(); logoutAndRedirect();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">{{ __('messages.header.logout') }}</a>
</div>

<script>
  document.getElementById('profileButton').addEventListener('click', function() {
      const dropdown = document.getElementById('profileDropdown');
      dropdown.classList.toggle('hidden');
  });

  document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('profileDropdown');
      const button = document.getElementById('profileButton');

      if (!button.contains(event.target) && !dropdown.contains(event.target)) {
          dropdown.classList.add('hidden');
      }
  });

  function logoutAndRedirect() {
      fetch("{{ route('admin.logout') }}", {
          method: "POST",
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      }).then(() => {
          window.location.reload();
          // window.location.replace("{{ route('admin.login') }}");
      });
  }
</script>
