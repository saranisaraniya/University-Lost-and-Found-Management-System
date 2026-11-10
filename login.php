<?php
include 'header.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, full_name, password_hash, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Standard Hash Check Or Direct Fallback for Admin Test Account
        $is_valid = password_verify($password, $row['password_hash']) || ($email === 'admin@unifind.edu' && $password === 'admin123');
        
        if ($is_valid) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] === 'admin') {
                echo "<script>window.location.href='admin.php';</script>";
            } else {
                echo "<script>window.location.href='dashboard.php';</script>";
            }
            exit;
        } else {
            $msg = "Invalid email or password.";
        }
    } else {
        $msg = "User account not found.";
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-3xl shadow-xl border border-luxury-gold/20 p-8 my-6 space-y-6">
    <div class="text-center space-y-2">
        <div class="w-14 h-14 bg-luxury-900 text-luxury-gold rounded-2xl flex items-center justify-center font-bold text-2xl mx-auto shadow-md border border-luxury-gold/30">
            <i class="fa-solid fa-right-to-bracket"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-luxury-900">Login to UniFind</h2>
        <p class="text-slate-500 text-xs">Single Gateway Portal for Students, Staff & Admins.</p>
    </div>

    <?php if($msg): ?>
        <div class="bg-red-50 text-red-600 border border-red-200 p-3.5 rounded-xl text-xs font-bold text-center">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
            <input type="email" name="email" required placeholder="admin@unifind.edu or student@vau.ac.lk" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-luxury-gold outline-none">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
            <input type="password" name="password" required placeholder="••••••••" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-luxury-gold outline-none">
        </div>
        <button type="submit" class="w-full btn-gold font-bold py-3 rounded-xl shadow-lg transition text-sm">
            Login to Account
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>