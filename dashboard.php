<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) { 
    echo "<script>window.location.href='login.php';</script>";
    exit; 
}
$user_id = $_SESSION['user_id'];
?>

<section class="space-y-6">
    <div class="bg-white rounded-2xl shadow-xl border border-luxury-gold/20 p-6 sm:p-8">
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl border-2 border-luxury-gold bg-luxury-900 text-luxury-gold flex items-center justify-center font-bold text-2xl shadow-md">
                    <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-luxury-900"><?php echo htmlspecialchars($_SESSION['full_name']); ?></h2>
                    <p class="text-sm text-slate-500">Account Role: <span class="capitalize font-bold text-luxury-goldDark"><?php echo htmlspecialchars($_SESSION['role']); ?></span></p>
                </div>
            </div>
            <a href="logout.php" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs px-4 py-2.5 rounded-xl font-bold border border-red-200 flex items-center transition"><i class="fa-solid fa-right-from-bracket mr-2"></i> Logout</a>
        </div>

        <div>
            <h3 class="text-lg font-bold text-luxury-900 mb-4 flex items-center"><i class="fa-solid fa-list-check text-luxury-gold mr-2.5"></i> My Submissions & Approval Status</h3>
            <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-sm">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-4">Type</th>
                            <th class="p-4">Item Name</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Approval Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res && $res->num_rows > 0) {
                            while($item = $res->fetch_assoc()) {
                                $typeBadge = ($item['report_type'] === 'lost') ? 'bg-red-100 text-red-700 border-red-300' : 'bg-emerald-100 text-emerald-700 border-emerald-300';
                                $appStatus = $item['approval_status'];
                                $appBadge = 'bg-slate-100 text-slate-700 border-slate-200';
                                if ($appStatus === 'pending') $appBadge = 'bg-amber-100 text-amber-800 border-amber-300';
                                elseif ($appStatus === 'approved') $appBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                elseif ($appStatus === 'rejected') $appBadge = 'bg-red-100 text-red-800 border-red-300';

                                echo "<tr>";
                                echo "<td class='p-4'><span class='px-3 py-1 rounded-full text-xs font-bold border {$typeBadge}'>" . strtoupper($item['report_type']) . "</span></td>";
                                echo "<td class='p-4 font-bold text-luxury-900'>" . htmlspecialchars($item['title']) . "</td>";
                                echo "<td class='p-4'>" . htmlspecialchars($item['category']) . "</td>";
                                echo "<td class='p-4'>" . htmlspecialchars($item['item_date']) . "</td>";
                                echo "<td class='p-4'><span class='px-3 py-1 rounded-full text-xs font-bold border {$appBadge}'>" . strtoupper($appStatus) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='p-6 text-center text-slate-400'>No submissions recorded yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
