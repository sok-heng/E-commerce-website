<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('./server/connection.php');

$sql = "SELECT 
            r.title, 
            r.description, 
            r.rating, 
            r.product_id, 
            u.user_name, 
            u.user_profile,
            p.name AS product_name,
            p.image_url AS product_image
        FROM reviews r
        JOIN users u ON r.user_id = u.user_id
        JOIN products p ON r.product_id = p.id
        ORDER BY r.id DESC";

$result = $conn->query($sql);

if (!$result) {
    echo "<p class='text-red-500'>Error fetching reviews: " . htmlspecialchars($conn->error) . "</p>";
    exit;
}

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
?>

<section class="py-12 sm:py-16 lg:py-20">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
            <div class="text-center">
                <p class="text-lg font-medium text-gray-600 font-pj"><span class="text-pink-400"><?= count($reviews) ?> </span>people have said how good our Products</p>
                <h2 class="mt-4 text-3xl font-bold text-gray-900 sm:text-4xl xl:text-5xl font-pj"><span class="text-pink-400">Our happy clients</span> say about us</h2>
            </div>

            <div class="relative mt-10 md:mt-24 md:order-2">
                <div class="absolute -inset-x-1 inset-y-16 md:-inset-x-2 md:-inset-y-6">
                    <div class="w-full h-full max-w-5xl mx-auto rounded-3xl opacity-30 blur-lg filter" style="background: linear-gradient(90deg, #44ff9a -0.55%, #44b0ff 22.86%, #8b44ff 48.36%, #ff6644 73.33%, #ebff70 99.34%)"></div>
                </div>

                <div id="reviewsGrid" class="relative grid grid-cols-1 gap-6 mx-auto md:max-w-none lg:gap-10 md:grid-cols-2 lg:grid-cols-3 max-w-lg md:max-w-none items-stretch">
                    <?php foreach ($reviews as $index => $review):
                        $filledStars = intval($review['rating']);
                        $emptyStars = 5 - $filledStars;
                        $hiddenClass = ($index >= 3) ? 'hidden' : '';

                        $avatar = !empty($review['user_profile']) ? htmlspecialchars($review['user_profile']) : 'https://cdn.rareblocks.xyz/collection/clarity/images/testimonial/4/avatar-male-1.png';
                        $productImage = !empty($review['product_image']) ? htmlspecialchars($review['product_image']) : '';
                        $productName = htmlspecialchars($review['product_name']);
                    ?>
                    <a href="product_detail.php?id=<?= urlencode($review['product_id']) ?>" class="block h-full hover:scale-[1.02] transition-transform duration-300 <?= $hiddenClass ?>" data-review-index="<?= $index ?>">
                        <div class="flex flex-col overflow-hidden shadow-xl w-full h-full">
                            <div class="flex flex-col justify-between flex-1 p-6 bg-white lg:py-8 lg:px-7 h-full">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <?php for ($i=0; $i < $filledStars; $i++): ?>
                                            <svg class="w-5 h-5 text-[#FDB241]" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                        <?php for ($i=0; $i < $emptyStars; $i++): ?>
                                            <svg class="w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <blockquote class="flex-1 mt-6">
                                        <p class="text-lg leading-relaxed text-gray-900 font-pj">“<?= htmlspecialchars($review['description']) ?>”</p>
                                    </blockquote>
                                </div>
                                <div class="flex items-center mt-6">
                                    <img class="flex-shrink-0 object-cover rounded-full w-11 h-11" src="<?= $avatar ?>" alt="User avatar" />
                                    <div class="ml-4">
                                        <p class="text-base font-bold text-gray-900 font-pj"><?= htmlspecialchars($review['user_name']) ?></p>
                                        <p class="mt-0.5 text-sm font-pj text-gray-600"><?= htmlspecialchars($review['title']) ?></p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <p class="text-sm text-gray-500 font-pj">Reviewed product:</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <img src="<?= $productImage ?>" alt="<?= $productName ?>" class="w-10 h-10 rounded-full object-cover" />
                                        <span class="text-sm font-semibold text-gray-800"><?= $productName ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <?php if(count($reviews) > 3): ?>
                <div class="mt-8 text-center">
                    <button id="showAllReviewsBtn" class="relative z-10 inline-block px-6 py-3 text-base font-bold leading-7 text-white bg-gray-900 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 font-pj">
                        Check all <?= count($reviews) ?> reviews
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('showAllReviewsBtn');
    if (btn) {
        btn.dataset.expanded = 'false';

        btn.addEventListener('click', function () {
            const cards = document.querySelectorAll('#reviewsGrid > a');

            if (this.dataset.expanded === 'true') {
                cards.forEach((card, i) => {
                    if (i >= 3) card.classList.add('hidden');
                });
                this.textContent = "Check all <?= count($reviews) ?> reviews";
                this.dataset.expanded = 'false';
            } else {
                cards.forEach(card => card.classList.remove('hidden'));
                this.textContent = "Show less";
                this.dataset.expanded = 'true';
            }
        });
    }
});
</script>
