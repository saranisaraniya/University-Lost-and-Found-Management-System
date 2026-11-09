<?php
include 'header.php';

$item_id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT i.*, u.full_name, u.email, u.phone, u.user_type, u.department FROM items i JOIN users u ON i.user_id = u.user_id WHERE i.item_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    echo "<div class='bg-red-50 text-red-600 p-6 rounded-2xl text-center font-bold my-8'>Item not found!</div>";
    include 'footer.php';
    exit;
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $proof = trim($_POST['verification_proof']);
    $claimant_id = $_SESSION['user_id'];

    $c_stmt = $conn->prepare("INSERT INTO claims (item_id, claimant_id, verification_proof) VALUES (?, ?, ?)");
    $c_stmt->bind_param("iis", $item_id, $claimant_id, $proof);
    if ($c_stmt->execute()) {
        $msg = "Ownership claim submitted successfully! Reporter will review details.";
    }
}
?>

<div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl border border-luxury-gold/20 overflow-hidden my-6">
    <div class="relative h-72 bg-slate-100">
        <img src="<?php echo !empty($item['image_path']) ? htmlspecialchars($item['image_path']) : 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?auto=format&fit=crop&w=600&q=80'; ?>" class="w-full h-full object-cover">
        <span class="absolute top-4 left-4 text-xs font-bold px-3.5 py-1.5 rounded-full text-white shadow-md <?php echo ($item['report_type'] === 'lost') ? 'badge-lost' : 'badge-found'; ?>"><?php echo strtoupper($item['report_type']); ?></span>
    </div>

    <div class="p-8 space-y-6">
        <div>
            <h2 class="text-3xl font-extrabold text-luxury-900 mb-3"><?php echo htmlspecialchars($item['title']); ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl text-xs border border-slate-100">
                <div><span class="font-bold text-slate-500 block">Category:</span> <?php echo htmlspecialchars($item['category']); ?></div>
                <div><span class="font-bold text-slate-500 block">Location:</span> <?php echo htmlspecialchars($item['location']); ?></div>
                <div><span class="font-bold text-slate-500 block">Date Reported:</span> <?php echo htmlspecialchars($item['item_date']); ?></div>
            </div>
        </div>

        <div>
            <h4 class="font-bold text-luxury-900 text-sm mb-1">Description & Details:</h4>
            <p class="text-slate-600 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h4 class="font-bold text-luxury-900 text-sm mb-3"><i class="fa-solid fa-address-book text-luxury-gold mr-2"></i> Reporter Info</h4>
            <div class="bg-luxury-900/5 p-4 rounded-2xl border border-luxury-gold/20 text-sm space-y-1">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($item['full_name']); ?> (<span class="capitalize font-semibold"><?php echo htmlspecialchars($item['user_type']); ?></span>)</p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($item['department']); ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>" class="text-luxury-goldDark underline font-medium"><?php echo htmlspecialchars($item['email']); ?></a></p>
                <?php if($item['phone']): ?><p><strong>Phone:</strong> <?php echo htmlspecialchars($item['phone']); ?></p><?php endif; ?>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h4 class="font-bold text-luxury-900 text-sm mb-3"><i class="fa-solid fa-hand-holding text-emerald-600 mr-2"></i> Submit Verification Claim</h4>
            <?php if($msg): ?><div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-3.5 rounded-xl text-xs font-bold mb-4 text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" action="details.php?id=<?php echo $item_id; ?>" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Proof of Ownership / Distinct Marks*</label>
                        <textarea name="verification_proof" required rows="3" placeholder="Provide proof (e.g. wallpaper picture, serial number)..." class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold"></textarea>
                    </div>
                    <button type="submit" class="w-full btn-gold font-bold py-3 rounded-xl shadow-md transition text-sm">Submit Claim Verification</button>
                </form>
            <?php else: ?>
                <p class="text-xs text-slate-500">Please <a href="login.php" class="text-luxury-goldDark font-bold underline">Login</a> to submit a claim.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
