<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) { 
    echo "<script>window.location.href='login.php';</script>";
    exit; 
}

$msg = "";
$err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $item_date = $_POST['item_date'];
    $user_id = $_SESSION['user_id'];
    $image_path = trim($_POST['image_url']);

    $stmt = $conn->prepare("INSERT INTO items (user_id, report_type, title, category, description, location, item_date, image_path, approval_status) VALUES (?, 'lost', ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issssss", $user_id, $title, $category, $description, $location, $item_date, $image_path);
    if ($stmt->execute()) { 
        $msg = "Lost report submitted successfully! Sent for Admin approval."; 
    } else {
        $err = "Error submitting report: " . $stmt->error;
    }
}
?>

<div class="max-w-lg mx-auto bg-white rounded-3xl shadow-xl border border-luxury-gold/20 p-8 my-6 space-y-4">
    <h3 class="text-2xl font-extrabold text-red-600 flex items-center"><i class="fa-solid fa-bullhorn mr-3"></i> Report Lost Item</h3>
    <p class="text-slate-500 text-xs">Your submission will be reviewed by Campus Admin before appearing publicly.</p>
    
    <?php if($msg): ?>
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-3.5 rounded-xl text-xs font-bold text-center"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <?php if($err): ?>
        <div class="bg-red-50 text-red-600 border border-red-200 p-3.5 rounded-xl text-xs font-bold text-center"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>

    <form method="POST" action="report_lost.php" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Item Title*</label>
            <input type="text" name="title" required placeholder="e.g. Sony Noise-Canceling Headphones" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Category*</label>
                <select name="category" required class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none bg-white focus:ring-2 focus:ring-luxury-gold">
                    <option value="Electronics">Electronics</option>
                    <option value="ID Cards">ID Cards / Docs</option>
                    <option value="Books/Stationery">Books/Stationery</option>
                    <option value="Personal Belongings">Personal Belongings</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Date Lost*</label>
                <input type="date" name="item_date" required class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Last Seen Location*</label>
            <input type="text" name="location" required placeholder="e.g. Computer Science Lab 2" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Image URL (Optional)</label>
            <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Description*</label>
            <textarea name="description" required rows="3" placeholder="Provide distinct identifiable marks, color..." class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold"></textarea>
        </div>
        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-md transition text-sm">Submit Report for Approval</button>
    </form>
</div>

<?php include 'footer.php'; ?>
