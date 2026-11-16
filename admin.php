<?php
include_once __DIR__ . '/config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Strict Authorization Guard: Admin users only
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    include 'header.php';
    echo "
    <div class='max-w-lg mx-auto bg-white rounded-3xl shadow-xl border border-red-200 p-8 my-12 text-center space-y-4'>
        <div class='w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-bold text-3xl mx-auto'>
            <i class='fa-solid fa-lock'></i>
        </div>
        <h2 class='text-2xl font-extrabold text-luxury-900'>Access Restricted!</h2>
        <p class='text-slate-600 text-sm leading-relaxed'>Intha Admin Panel page-ai <strong>Admin Accounts</strong> mattumae access seyya mudium. Normal student accounts-al intha page-ai paarkka mudiyaadhu.</p>
        <a href='login.php' class='btn-gold font-bold px-6 py-3 rounded-xl text-sm transition inline-flex items-center shadow-md'>
            <i class='fa-solid fa-right-to-bracket mr-2'></i> Login as Admin
        </a>
    </div>";
    include 'footer.php';
    exit;
}

// Action Processing (Approve, Reject, Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $act_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        $up = $conn->prepare("UPDATE items SET approval_status = 'approved' WHERE item_id = ?");
        $up->bind_param("i", $act_id);
        $up->execute();
    } elseif ($action === 'reject') {
        $up = $conn->prepare("UPDATE items SET approval_status = 'rejected' WHERE item_id = ?");
        $up->bind_param("i", $act_id);
        $up->execute();
    } elseif ($action === 'delete') {
        $del = $conn->prepare("DELETE FROM items WHERE item_id = ?");
        $del->bind_param("i", $act_id);
        $del->execute();
    }
    header("Location: admin.php");
    exit;
}

$cntRes = $conn->query("SELECT COUNT(*) as pending_cnt FROM items WHERE approval_status = 'pending'");
$pendingCnt = $cntRes->fetch_assoc()['pending_cnt'] ?? 0;

include 'header.php';
?>

<section class="space-y-6">
    <div class="bg-white rounded-2xl shadow-xl border border-luxury-gold/20 p-6 sm:p-8">
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-luxury-900 flex items-center">
                    <i class="fa-solid fa-user-shield text-luxury-gold mr-3"></i> System Executive Admin Panel
                </h2>
                <p class="text-slate-500 text-sm">Review, approve, or remove student and staff submissions.</p>
            </div>
            <span class="bg-amber-50 text-amber-800 text-xs border border-amber-300 px-4 py-2 rounded-full font-bold shadow-sm">
                <?php echo $pendingCnt; ?> Submissions Pending Approval
            </span>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-luxury-900 text-luxury-gold font-bold border-b border-luxury-gold/30">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Item & Reporter</th>
                        <th class="p-4">User Role</th>
                        <th class="p-4">Reg No / ID & Dept</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php
                    $res = $conn->query("SELECT i.*, u.full_name, u.user_type, u.reg_no_or_staff_id, u.department FROM items i JOIN users u ON i.user_id = u.user_id ORDER BY i.created_at DESC");
                    if ($res && $res->num_rows > 0) {
                        while($row = $res->fetch_assoc()) {
                            $appStatus = $row['approval_status'];
                            $appBadge = 'bg-slate-100 text-slate-700 border-slate-200';
                            if ($appStatus === 'pending') $appBadge = 'bg-amber-100 text-amber-800 border-amber-300';
                            elseif ($appStatus === 'approved') $appBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                            elseif ($appStatus === 'rejected') $appBadge = 'bg-red-100 text-red-800 border-red-300';

                            $itemId = $row['item_id'];
                            $title = htmlspecialchars($row['title']);
                            $fullName = htmlspecialchars($row['full_name']);
                            $userType = htmlspecialchars($row['user_type']);
                            $regNo = htmlspecialchars($row['reg_no_or_staff_id'] ?? 'N/A');
                            $dept = htmlspecialchars($row['department']);
                            $statusUpper = strtoupper($appStatus);

                            echo "<tr>";
                            echo "<td class='p-4 font-bold text-slate-400'>#{$itemId}</td>";
                            echo "<td class='p-4'><div class='font-bold text-luxury-900'>{$title}</div><div class='text-xs text-slate-400'>By: {$fullName}</div></td>";
                            echo "<td class='p-4'><span class='capitalize font-bold text-xs bg-slate-100 px-3 py-1 rounded-lg border border-slate-200'>{$userType}</span></td>";
                            echo "<td class='p-4'><div class='font-semibold text-slate-700'>{$regNo}</div><div class='text-xs text-slate-400'>{$dept}</div></td>";
                            echo "<td class='p-4'><span class='px-3 py-1 rounded-full text-xs font-bold border {$appBadge}'>{$statusUpper}</span></td>";
                            echo "<td class='p-4 text-right space-x-1.5'>";
                            if ($appStatus !== 'approved') {
                                echo "<a href='admin.php?action=approve&id={$itemId}' class='bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-sm inline-block'>Approve</a> ";
                            }
                            if ($appStatus !== 'rejected') {
                                echo "<a href='admin.php?action=reject&id={$itemId}' class='bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-sm inline-block'>Reject</a> ";
                            }
                            echo "<a href='admin.php?action=delete&id={$itemId}' class='bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-sm inline-block'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='p-6 text-center text-slate-400'>No reports submitted yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
