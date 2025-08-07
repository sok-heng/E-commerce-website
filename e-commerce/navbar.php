<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./server/connection.php');

// Default user display
$profileImage = "/uploads/default_profile.jpg";
$userName = "User";
$userEmail = "";

// If logged in, get user info
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT user_profile, user_name, user_email FROM users WHERE user_id = ? LIMIT 1");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($user_profile, $user_name, $user_email);

    if ($stmt->fetch()) {
        if (!empty($user_profile)) {
            $profileImage = $user_profile;
        }
        $userName = $user_name;
        $userEmail = $user_email;
    }
    $stmt->close();
}
function limit_text($text, $limit = 50) {
    if (strlen($text) <= $limit) {
        return $text;
    }
    return substr($text, 0, $limit) . '...';
}

// Cart setup
$cart = $_SESSION['cart'] ?? [];
$cartCount = count($cart);
$total = 0;

// Begin output
echo '
<nav class="bg-pink-200 bg-opacity-85 sticky top-0 z-50">
  <div class="p-4 sm:p-4 md:p-0 md:px-5 max-w-screen-xl flex flex-wrap items-center justify-between mx-auto">
    <a href="homepage.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <span class="self-center text-2xl font-semibold whitespace-nowrap"><span class="text-pink-400">Bloom</span>Skin</span>
    </a>

    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">';

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    echo '
    <!-- Cart Button -->
    <div class="sm:p-5 relative">
      <button id="cartDropdownButton" data-dropdown-toggle="cartDropdown"
        class="relative flex items-center justify-center p-2 rounded hover:bg-gray-200" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-6 h-6 text-gray-700" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>';
    if ($cartCount > 0) {
        echo '<span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1 rounded-full font-bold">' . $cartCount . '</span>';
    }
    echo '
      </button>

      <!-- Cart Dropdown -->
      <div id="cartDropdown"
           class="z-10 hidden absolute right-0 mt-2 w-72 bg-white divide-y divide-gray-100 rounded-lg shadow-lg border border-gray-200">';

    if ($cartCount > 0) {
        foreach ($cart as $item) {
            $subtotal = $item["price"] * $item["quantity"];
            $total += $subtotal;
            
            echo '
        <div class="flex items-start p-4 gap-3 hover:bg-gray-50 relative">
          <img src="' . htmlspecialchars($item["image"]) . '" class="w-12 h-12 object-cover" alt="' . htmlspecialchars($item["name"]) . '">
          <div class="flex-grow text-sm">
            <div class="font-semibold">' . htmlspecialchars($item["name"]) . '</div>
            <div class="text-gray-500 truncate line-clamp-1 text-xs">' . htmlspecialchars(limit_text($item["description1"],10)) . '</div>
            <div class="text-gray-400 text-xs">Qt: ' . $item["quantity"] . '</div>
          </div>
          <div>
            <div class="text-right text-sm font-bold text-gray-700">$' . number_format($subtotal, 2) . '</div>
            <form method="GET" action="addtocart.php">
              <input type="hidden" name="remove" value="' . htmlspecialchars($item["id"]) . '">
              <input type="hidden" name="openCart" value="1">
              <button type="submit" class="text-xs text-red-500 hover:text-red-700 mt-5 p-2 rounded-sm hover:bg-pink-300 hover:text-white border border-pink-300">Remove</button>
            </form>
          </div>
        </div>';
        }

        echo '
        <div class="p-2 border-t border-gray-200 flex justify-center bg-pink-300">
          <form method="GET" action="addtocart.php">
            <input type="hidden" name="clear_cart" value="1">
            <input type="hidden" name="openCart" value="1">
            <button type="submit" class="transition-all duration-700 text-sm text-white hover:text-pink-800 font-semibold px-3 py-1 rounded border border-white hover:border-pink-800 hover:bg-pink-300">
              Delete All
            </button>
          </form>
        </div>';

        echo '
        <div class="p-4 text-center">
          <a href="cart.php" class="block w-full bg-transparent hover:bg-pink-200 text-pink-400 hover:text-white rounded shadow hover:shadow-lg py-2 px-4 border border-pink-300 hover:border-transparent">
            Checkout $' . number_format($total, 2) . '
          </a>
        </div>';
    } else {
        echo '<div class="p-4 text-center text-sm text-gray-500">Your cart is empty.</div>';
    }

    echo '
      </div>
    </div>

    <!-- User Menu -->
    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom" aria-expanded="false">
      <span class="sr-only">Open user menu</span>
      <img class="w-8 h-8 rounded-full object-cover" src="' . htmlspecialchars($profileImage) . '" alt="User photo">
    </button>

    <div class="z-50 hidden my-4 text-base list-none bg-gradient-to-tr from-pink-200 via-pink-200 divide-y rounded-lg shadow" id="user-dropdown">
      <div class="px-4 py-3">
        <span class="block text-sm text-black">' . htmlspecialchars($userName) . '</span>
        <span class="block text-sm text-black truncate">' . htmlspecialchars($userEmail) . '</span>
      </div>
      <ul class="py-2" aria-labelledby="user-menu-button">';

    if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin") {
        echo '<li><a href="./setting_admin.php" class="block px-4 py-2 text-sm text-black hover:bg-gray-100">Admin Setting</a></li>';
    } else {
        echo '<li><a href="./setting.php" class="block px-4 py-2 text-sm text-black hover:bg-gray-100">Profile Setting</a></li>';
    }

    echo '
        <li><a href="logout.php" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-100">Sign out</a></li>
      </ul>
    </div>

    <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm bg-white text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-user" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>';
} else {
    echo '<a href="loginpage.php" class="md:m-5 text-pink-200 bg-white px-4 py-2 rounded hover:bg-pink-300 transition">Login</a>';
}

echo '
    </div>

    <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1 " id="navbar-user">
      <ul class="flex flex-col md:flex-row md:space-x-8 font-medium mt-4 md:mt-0">
        <li class="hover:bg-white p-2 rounded-r  "><a href="./homepage.php"><div class="flex items-center gap-2 "><i class="fa-solid fa-house text-gray-900 hover:text-blue-700"></i><p class="block py-2 px-3 md:p-0 text-gray-900 hover:text-blue-700">Home</p></div></a></li>
        <li class="hover:bg-white p-2 rounded-r "><a href="./productpage.php"><div class="flex items-center gap-2"><i class="fa-brands fa-product-hunt"></i><p class="block py-2 px-3 md:p-0 text-gray-900 hover:text-blue-700">Products</p></div></a></li>
        <li class="hover:bg-white p-2 rounded-r "><a href="./about_us.php"><div class="flex items-center gap-2"><i class="fa-solid fa-address-card"></i><p class="block py-2 px-3 md:p-0 text-gray-900 hover:text-blue-700">About Us</p></div></a></li>
        <li class="hover:bg-white p-2 rounded-r "><a href="./contact_us.php"><div class="flex items-center gap-2"><i class="fa-solid fa-address-book"></i><p class="block py-2 px-3 md:p-0 text-gray-900 hover:text-blue-700">Contact</p></div></a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Search Bar Centered Below Navbar -->
<div class="flex justify-center mt-4 mb-6">
  <div class="relative w-full max-w-lg px-4">
      <input type="text" name="query" id="liveSearchInput" placeholder="Search products..." autocomplete="off"
        class="w-full px-4 py-2 rounded-md border border-pink-200 focus:outline-none focus:ring-2 focus:ring-pink-300 text-sm" required>
    <div id="liveSearchResults" class="absolute left-0 mt-2 w-full bg-white border border-pink-200 rounded shadow-lg z-50 hidden max-h-64 overflow-y-auto">
      <!-- Results will be inserted here -->
    </div>
  </div>
</div>

<!-- ✅ JavaScript -->
<script>
  const input = document.getElementById("liveSearchInput");
  const resultsBox = document.getElementById("liveSearchResults");
  let debounceTimer;

  input?.addEventListener("input", function () {
    clearTimeout(debounceTimer);
    const query = this.value.trim();

    if (query.length === 0) {
      resultsBox.innerHTML = "";
      resultsBox.classList.add("hidden");
      return;
    }

    debounceTimer = setTimeout(() => {
      fetch(`search_handler.php?query=${encodeURIComponent(query)}`)
        .then((res) => res.text())
        .then((data) => {
          resultsBox.innerHTML = data;
          resultsBox.classList.remove("hidden");
        });
    }, 300);
  });

  document.addEventListener("click", function (event) {
    if (!input.contains(event.target) && !resultsBox.contains(event.target)) {
      resultsBox.classList.add("hidden");
    }
  });

  let selectedIndex = -1;
  input?.addEventListener("keydown", function (e) {
    const items = resultsBox.querySelectorAll("a");
    const maxIndex = items.length - 1;

    if (e.key === "ArrowDown") {
      e.preventDefault();
      selectedIndex = selectedIndex < maxIndex ? selectedIndex + 1 : 0;
      highlightSelected(items);
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      selectedIndex = selectedIndex > 0 ? selectedIndex - 1 : maxIndex;
      highlightSelected(items);
    } else if (e.key === "Enter") {
      if (selectedIndex >= 0 && items[selectedIndex]) {
        e.preventDefault();
        window.location.href = items[selectedIndex].href;
      }
    }
  });

  function highlightSelected(items) {
    items.forEach((item, index) => {
      if (index === selectedIndex) {
        item.classList.add("bg-pink-100");
        item.scrollIntoView({ block: "nearest" });
      } else {
        item.classList.remove("bg-pink-100");
      }
    });
  }

  input?.addEventListener("input", () => {
    selectedIndex = -1;
  });
</script>
';
?>
