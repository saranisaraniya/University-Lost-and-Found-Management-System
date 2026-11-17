<?php
include 'header.php';

$msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $user_type = trim($_POST['user_type']);
    $reg = trim($_POST['reg_no_or_staff_id']);
    $dept = trim($_POST['department']);
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, user_type, reg_no_or_staff_id, department, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $user_type, $reg, $dept, $hash);
    if ($stmt->execute()) {
        $success_msg = "Registration successful! <a href='login.php' class='underline font-bold text-emerald-800'>Login now</a>";
    } else {
        $msg = "Error creating account or Email already registered.";
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-3xl shadow-xl border border-luxury-gold/20 p-8 my-6 space-y-6">
    <div class="text-center space-y-2">
        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold text-2xl mx-auto border border-emerald-200">
            <i class="fa-solid fa-id-card"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-luxury-900">Student & Staff Portal</h2>
        <p class="text-slate-500 text-xs">Create an account to report items & claim lost property.</p>
    </div>

    <?php if($msg): ?><div class="bg-red-50 text-red-600 border border-red-200 p-3 rounded-xl text-xs font-bold text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
    <?php if($success_msg): ?><div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-3 rounded-xl text-xs font-bold text-center"><?php echo $success_msg; ?></div><?php endif; ?>

    <form method="POST" action="register.php" class="space-y-3.5">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Role / Account Type*</label>
            <select name="user_type" id="user_type" onchange="toggleRegField()" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none bg-white focus:ring-2 focus:ring-luxury-gold">
                <option value="student">Student Account</option>
                <option value="staff">Academic / Non-Academic Staff</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name*</label>
            <input type="text" name="full_name" required placeholder="Kavinda Perera" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address*</label>
            <input type="email" name="email" required placeholder="kavinda@vau.ac.lk" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label id="reg_label" class="block text-xs font-bold text-slate-700 mb-1">Student Reg No*</label>
                <input type="text" id="reg_input" name="reg_no_or_staff_id" required placeholder="e.g. 2021/ICT/85" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="+94 77 000 0000" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Faculty / Department*</label>
            <input type="text" name="department" value="Physical Science Department" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Password*</label>
            <input type="password" name="password" required placeholder="••••••••" class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-luxury-gold">
        </div>
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-md transition text-sm">Register Account</button>
    </form>
</div>

<?php include 'footer.php'; ?>
