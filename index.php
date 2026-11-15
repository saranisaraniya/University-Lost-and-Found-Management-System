<?php include 'header.php'; ?>

<section class="space-y-8">
    <div class="relative bg-gradient-to-r from-luxury-900 via-luxury-800 to-luxury-950 rounded-3xl p-8 sm:p-12 text-white shadow-2xl overflow-hidden border border-luxury-gold/30">
        <div class="absolute -right-12 -bottom-12 w-96 h-96 bg-luxury-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl space-y-4">
            <span class="bg-luxury-gold/20 text-luxury-goldLight border border-luxury-gold/40 text-xs px-3.5 py-1 rounded-full font-bold inline-flex items-center tracking-wide">
                <i class="fa-solid fa-shield-halved mr-1.5 text-luxury-gold"></i> Official Campus Retrieval Platform
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                Lost or Found Something on <span class="bg-gradient-to-r from-luxury-gold to-amber-200 bg-clip-text text-transparent">Campus?</span>
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                Connect seamlessly with student and staff reports. All entries are reviewed by campus administration before being publicly published to ensure authenticity.
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="report_lost.php" class="bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center text-sm">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Report Lost Item
                </a>
                <a href="report_found.php" class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center text-sm">
                    <i class="fa-solid fa-hand-holding-heart mr-2"></i> Report Found Item
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-5 border border-luxury-gold/20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-6 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-luxury-gold"></i>
                <input type="text" id="search-input" onkeyup="filterListings()" placeholder="Search title, last seen location, details..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-luxury-gold focus:outline-none shadow-sm">
            </div>
            <div class="md:col-span-3">
                <select id="category-filter" onchange="filterListings()" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-luxury-gold focus:outline-none shadow-sm">
                    <option value="">All Categories</option>
                    <option value="Electronics">Electronics</option>
                    <option value="ID Cards">ID Cards & Documents</option>
                    <option value="Books/Stationery">Books & Stationery</option>
                    <option value="Personal Belongings">Personal Belongings</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <select id="type-filter" onchange="filterListings()" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-luxury-gold focus:outline-none shadow-sm">
                    <option value="">All Submissions</option>
                    <option value="lost">Lost Items Only</option>
                    <option value="found">Found Items Only</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-luxury-900 flex items-center">
                Verified Campus Listings
                <span id="item-count" class="ml-3 bg-luxury-gold/15 text-luxury-goldDark text-xs px-3 py-1 rounded-full font-bold border border-luxury-gold/30"></span>
            </h2>
        </div>

        <div id="items-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $sql = "SELECT i.*, u.full_name, u.user_type FROM items i JOIN users u ON i.user_id = u.user_id WHERE i.approval_status = 'approved' AND i.status != 'returned' ORDER BY i.created_at DESC";
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0) {
                while($item = $res->fetch_assoc()) {
                    $img = !empty($item['image_path']) ? htmlspecialchars($item['image_path']) : 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?auto=format&fit=crop&w=600&q=80';
                    $badgeClass = ($item['report_type'] === 'lost') ? 'badge-lost' : 'badge-found';
                    ?>
                    <div class="luxury-card rounded-2xl overflow-hidden flex flex-col" data-category="<?php echo htmlspecialchars($item['category']); ?>" data-type="<?php echo htmlspecialchars($item['report_type']); ?>">
                        <div class="relative h-52 bg-slate-100 overflow-hidden">
                            <img src="<?php echo $img; ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            <span class="absolute top-3 left-3 text-xs font-bold px-3 py-1 rounded-full text-white shadow-lg <?php echo $badgeClass; ?>">
                                <?php echo strtoupper($item['report_type']); ?>
                            </span>
                            <span class="absolute top-3 right-3 text-xs font-bold px-3 py-1 rounded-full bg-luxury-900/85 text-luxury-goldLight shadow-md border border-luxury-gold/30 capitalize">
                                <?php echo htmlspecialchars($item['user_type']); ?>
                            </span>
                        </div>
                        <div class="p-6 flex flex-col flex-grow space-y-3">
                            <h3 class="text-xl font-bold text-luxury-900"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <div class="space-y-1 text-xs text-slate-500">
                                <p><i class="fa-solid fa-tag text-luxury-gold mr-2"></i><?php echo htmlspecialchars($item['category']); ?></p>
                                <p><i class="fa-solid fa-location-dot text-luxury-gold mr-2"></i><?php echo htmlspecialchars($item['location']); ?></p>
                                <p><i class="fa-solid fa-calendar text-luxury-gold mr-2"></i><?php echo htmlspecialchars($item['item_date']); ?></p>
                            </div>
                            <p class="text-sm text-slate-600 line-clamp-2 flex-grow pt-1"><?php echo htmlspecialchars($item['description']); ?></p>
                            <a href="details.php?id=<?php echo $item['item_id']; ?>" class="w-full text-center bg-luxury-900 hover:bg-luxury-800 text-luxury-gold font-bold text-sm py-3 rounded-xl border border-luxury-gold/40 transition shadow-sm block">
                                View Details & Claim
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='col-span-full text-center text-slate-500 py-12 bg-white rounded-2xl border border-slate-200'>Currently no active verified listings available.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
