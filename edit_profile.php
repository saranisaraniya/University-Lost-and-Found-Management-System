<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) { 
    echo "<script>window.location.href='login.php';</script>";
    exit; 
}
$user_id = $_SESSION['user_id'];

$msg = "";
$err = "";

// Handle Profile Update Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = trim($_POST['full_name']);
    $new_phone = trim($_POST['phone']);
    $new_dept = trim($_POST['department']);

    $up_stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, department = ? WHERE user_id = ?");
    $up_stmt->bind_param("sssi", $new_name, $new_phone, $new_dept, $user_id);
    if ($up_stmt->execute()) {
        $_SESSION['full_name'] = $new_name;
        $msg = "Profile information updated successfully!";
    } else {
        $err = "Failed to update profile details.";
    }
}

// Fetch Current User Details
$u_stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$u_stmt->bind_param("i", $user_id);
$u_stmt->execute();
$current_user = $u_stmt->get_result()->fetch_assoc();
?>

<div class="max-w-xl mx-auto bg-white rounded-3xl shadow-xl border border-luxury-gold/20 p-8 my-6 space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-luxury-900 text-luxury-gold rounded-2xl flex items-center justify-center font-bold text-xl shadow-md border border-luxury-gold/30">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-luxury-900">Edit Profile</h2>
                <p class="text-xs text-slate-500">Update your account details and contact information.</p>
            </div>
        </div>
        <a href="dashboard.php" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl font-bold transition flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Dashboard
        </a>
    </div>

    <?php if($msg): ?>
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-3.5 rounded-xl text-xs font-bold text-center">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>
    <?php if($err): ?>
        <div class="bg-red-50 text-red-600 border border-red-200 p-3.5 rounded-xl text-xs font-bold text-center">
            <?php echo htmlspecialchars($err); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_profile.php" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name*</label>
            <input type="text" name="full_name" required value="<?php echo htmlspecialchars($current_user['full_name']); ?>" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-luxury-gold outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address (Read-only)</label>
            <input type="email" value="<?php echo htmlspecialchars($current_user['email']); ?>" disabled class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Reg No / Staff ID (Read-only)</label>
                <input type="text" value="<?php echo htmlspecialchars($current_user['reg_no_or_staff_id'] ?? 'N/A'); ?>" disabled class="w-full border border-slate-200 rounded-xl p-3 text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>" placeholder="+94 77 000 0000" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-luxury-gold outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Faculty / Department*</label>
            <input type="text" name="department" required value="<?php echo htmlspecialchars($current_user['department']); ?>" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-luxury-gold outline-none">
        </div>

        <div class="pt-2 flex space-x-3">
            <button type="submit" class="flex-1 btn-gold font-bold py-3 rounded-xl shadow-lg transition text-sm flex items-center justify-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
            </button>
            <a href="dashboard.php" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-6 py-3 rounded-xl transition text-sm text-center">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
