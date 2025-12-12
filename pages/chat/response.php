<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid Request');
}

$message = strtolower(trim($_POST['message']));
$userName = $_SESSION['nama'] ?? 'Bos';

// Greeting Responses
if (preg_match('/\b(hai|halo|hello|hi|hey|pagi|siang|sore|malam)\b/', $message)) {
    $greetings = [
        "Halo <strong>$userName</strong>! 👋 Saya <strong>Geprek AI</strong>, asisten virtual Anda. Ada yang bisa saya bantu hari ini?",
        "Hai <strong>$userName</strong>! Senang bisa membantu Anda. Saya <strong>Geprek AI</strong> siap memberikan informasi bisnis Anda! 😊",
        "Halo! Saya <strong>Geprek AI</strong>, asisten cerdas untuk bisnis geprek Anda. Ada pertanyaan tentang bisnis hari ini?",
    ];
    echo $greetings[array_rand($greetings)];
    exit;
}

// Thank You Responses
if (preg_match('/\b(terima kasih|thanks|thank you|makasih|thx)\b/', $message)) {
    $thanks = [
        "Sama-sama <strong>$userName</strong>! 😊 Senang bisa membantu. Jangan ragu untuk bertanya lagi ya!",
        "Dengan senang hati! Saya <strong>Geprek AI</strong> selalu siap membantu Anda. 🙌",
        "Terima kasih kembali! Semoga informasinya bermanfaat untuk bisnis Anda. 💼",
    ];
    echo $thanks[array_rand($thanks)];
    exit;
}

// Who are you / Siapa kamu
if (preg_match('/\b(siapa|who|kamu|you|apa|what)\b/', $message) && preg_match('/\b(kamu|you|nama|name)\b/', $message)) {
    echo "Saya adalah <strong>Geprek AI</strong> 🤖, asisten virtual yang dirancang khusus untuk membantu mengelola bisnis geprek Anda.<br><br>";
    echo "Saya bisa membantu Anda dengan:<br>";
    echo "<span class='material-symbols-outlined' style='vertical-align: middle; color: #FFCC00;'>payments</span> Informasi penghasilan<br>";
    echo "<span class='material-symbols-outlined' style='vertical-align: middle; color: #FFCC00;'>emoji_events</span> Menu terlaris<br>";
    echo "<span class='material-symbols-outlined' style='vertical-align: middle; color: #FFCC00;'>inventory_2</span> Status stok bahan<br>";
    echo "<span class='material-symbols-outlined' style='vertical-align: middle; color: #FFCC00;'>analytics</span> Data transaksi";
    exit;
}

// Help Command
if (preg_match('/\b(help|bantuan|tolong|bisa apa|fitur)\b/', $message)) {
    echo "<strong>🎯 Yang bisa saya bantu:</strong><br><br>";
    echo "💰 <strong>Penghasilan</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Penghasilan hari ini\"<br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Penghasilan kemarin\"<br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Penghasilan bulan ini\"<br><br>";
    echo "🏆 <strong>Menu Terlaris</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Menu terlaris\"<br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Menu paling laku\"<br><br>";
    echo "📦 <strong>Stok Bahan</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Stok menipis\"<br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Bahan yang habis\"<br><br>";
    echo "📊 <strong>Transaksi</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Total transaksi hari ini\"<br>";
    echo "&nbsp;&nbsp;&nbsp;• \"Total menu\"";
    exit;
}

// Income/Revenue Query
if (strpos($message, 'penghasilan') !== false || strpos($message, 'income') !== false || strpos($message, 'revenue') !== false || strpos($message, 'omset') !== false) {
    if (strpos($message, 'kemarin') !== false || strpos($message, 'yesterday') !== false) {
        $date = date('Y-m-d', strtotime('-1 day'));
        $label = "kemarin";
        $dateDisplay = date('d M Y', strtotime($date));
    } elseif (strpos($message, 'bulan') !== false || strpos($message, 'month') !== false) {
        $month = date('m');
        $year = date('Y');
        $date = "$year-$month-%";
        $label = "bulan ini";
        $dateDisplay = date('F Y');
    } elseif (strpos($message, 'minggu') !== false || strpos($message, 'week') !== false) {
        $startWeek = date('Y-m-d', strtotime('monday this week'));
        $endWeek = date('Y-m-d', strtotime('sunday this week'));
        $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(created_at) BETWEEN '$startWeek' AND '$endWeek'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $total = $row['total'] ?? 0;
        
        echo "<div style='padding: 1rem; background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%); border-left: 4px solid #FFCC00; border-radius: 8px;'>";
        echo "<strong style='font-size: 1.1em;'>📊 Penghasilan Minggu Ini</strong><br>";
        echo "<span style='color: #6c757d; font-size: 0.9em;'>(" . date('d M', strtotime($startWeek)) . " - " . date('d M Y', strtotime($endWeek)) . ")</span><br><br>";
        echo "<span style='font-size: 1.8em; font-weight: bold; color: #FFCC00;'>Rp " . number_format($total, 0, ',', '.') . "</span>";
        echo "</div>";
        exit;
    } else {
        $date = date('Y-m-d');
        $label = "hari ini";
        $dateDisplay = date('d M Y');
    }

    // Query execution
    if (strpos($label, 'bulan') !== false) {
        $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE created_at LIKE '$date'";
    } else {
        $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(created_at) = '$date'";
    }

    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $total = $row['total'] ?? 0;

    // Get transaction count
    if (strpos($label, 'bulan') !== false) {
        $countQuery = "SELECT COUNT(*) as count FROM transaksi WHERE created_at LIKE '$date'";
    } else {
        $countQuery = "SELECT COUNT(*) as count FROM transaksi WHERE DATE(created_at) = '$date'";
    }
    $countResult = $conn->query($countQuery);
    $countRow = $countResult->fetch_assoc();
    $transCount = $countRow['count'] ?? 0;

    echo "<div style='padding: 1rem; background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%); border-left: 4px solid #FFCC00; border-radius: 8px;'>";
    echo "<strong style='font-size: 1.1em;'>💰 Penghasilan " . ucfirst($label) . "</strong><br>";
    echo "<span style='color: #6c757d; font-size: 0.9em;'>$dateDisplay</span><br><br>";
    echo "<span style='font-size: 1.8em; font-weight: bold; color: #FFCC00;'>Rp " . number_format($total, 0, ',', '.') . "</span><br>";
    echo "<span style='color: #6c757d; font-size: 0.9em;'>Dari $transCount transaksi</span>";
    echo "</div>";

} elseif (strpos($message, 'laris') !== false || strpos($message, 'laku') !== false || strpos($message, 'best') !== false || strpos($message, 'populer') !== false) {
    // Best Selling Menu
    $query = "SELECT m.nama, SUM(dt.jumlah) as total_sold, m.harga
              FROM detail_transaksi dt 
              JOIN menu m ON dt.menu_id = m.id 
              GROUP BY dt.menu_id 
              ORDER BY total_sold DESC 
              LIMIT 5";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div style='padding: 1rem; background: linear-gradient(135deg, #fff3e0 0%, #ffffff 100%); border-left: 4px solid #ff9800; border-radius: 8px;'>";
        echo "<strong style='font-size: 1.1em;'>🏆 Top 5 Menu Terlaris</strong><br><br>";
        
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $medal = $no == 1 ? "🥇" : ($no == 2 ? "🥈" : ($no == 3 ? "🥉" : "📍"));
            $revenue = $row['total_sold'] * $row['harga'];
            
            echo "<div style='margin-bottom: 0.75rem; padding: 0.5rem; background: white; border-radius: 6px;'>";
            echo "$medal <strong>" . $row['nama'] . "</strong><br>";
            echo "<span style='color: #6c757d; font-size: 0.9em;'>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Terjual: <strong>" . $row['total_sold'] . "</strong> porsi";
            echo " • Revenue: <strong>Rp " . number_format($revenue, 0, ',', '.') . "</strong>";
            echo "</span>";
            echo "</div>";
            $no++;
        }
        echo "</div>";
    } else {
        echo "<span style='color: #6c757d;'>📭 Belum ada data penjualan.</span>";
    }

} elseif (strpos($message, 'stok') !== false || strpos($message, 'habis') !== false || strpos($message, 'bahan') !== false) {
    // Low Stock
    $threshold = 10;
    $query = "SELECT nama, stok, satuan FROM bahan_baku WHERE stok <= $threshold ORDER BY stok ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div style='padding: 1rem; background: linear-gradient(135deg, #ffebee 0%, #ffffff 100%); border-left: 4px solid #ef5350; border-radius: 8px;'>";
        echo "<strong style='font-size: 1.1em;'>⚠️ Stok Menipis</strong><br>";
        echo "<span style='color: #6c757d; font-size: 0.9em;'>Bahan di bawah $threshold unit</span><br><br>";
        
        while ($row = $result->fetch_assoc()) {
            $stockStatus = $row['stok'] == 0 ? "🔴 HABIS" : ($row['stok'] <= 5 ? "🟠 KRITIS" : "🟡 RENDAH");
            echo "<div style='margin-bottom: 0.5rem; padding: 0.5rem; background: white; border-radius: 6px;'>";
            echo "$stockStatus <strong>" . $row['nama'] . "</strong><br>";
            echo "<span style='color: #6c757d; font-size: 0.9em;'>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Sisa: <strong>" . $row['stok'] . " " . $row['satuan'] . "</strong>";
            echo "</span>";
            echo "</div>";
        }
        echo "<br><span style='color: #d32f2f; font-size: 0.9em;'>💡 Segera lakukan restock!</span>";
        echo "</div>";
    } else {
        echo "<div style='padding: 1rem; background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%); border-left: 4px solid #4caf50; border-radius: 8px;'>";
        echo "✅ <strong>Stok Aman!</strong><br>";
        echo "<span style='color: #6c757d; font-size: 0.9em;'>Semua bahan baku di atas $threshold unit.</span>";
        echo "</div>";
    }

} elseif (strpos($message, 'transaksi') !== false) {
    // Transaction Count
    $date = date('Y-m-d');
    $dateDisplay = date('d M Y');
    
    $query = "SELECT COUNT(*) as total, SUM(total_bayar) as revenue FROM transaksi WHERE DATE(created_at) = '$date'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    
    $totalTrans = $row['total'] ?? 0;
    $revenue = $row['revenue'] ?? 0;
    $avgTransaction = $totalTrans > 0 ? $revenue / $totalTrans : 0;

    echo "<div style='padding: 1rem; background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); border-left: 4px solid #1976d2; border-radius: 8px;'>";
    echo "<strong style='font-size: 1.1em;'>📊 Transaksi Hari Ini</strong><br>";
    echo "<span style='color: #6c757d; font-size: 0.9em;'>$dateDisplay</span><br><br>";
    echo "📝 Total Transaksi: <strong>$totalTrans</strong><br>";
    echo "💰 Total Revenue: <strong>Rp " . number_format($revenue, 0, ',', '.') . "</strong><br>";
    echo "📈 Rata-rata/Transaksi: <strong>Rp " . number_format($avgTransaction, 0, ',', '.') . "</strong>";
    echo "</div>";

} elseif ((strpos($message, 'menu') !== false || strpos($message, 'produk') !== false) && (strpos($message, 'total') !== false || strpos($message, 'berapa') !== false || strpos($message, 'jumlah') !== false)) {
    // Total Menu Count
    $result = $conn->query("SELECT COUNT(*) as total, 
                                   SUM(CASE WHEN status='tersedia' THEN 1 ELSE 0 END) as available,
                                   SUM(CASE WHEN status='habis' THEN 1 ELSE 0 END) as out_of_stock
                            FROM menu");
    $row = $result->fetch_assoc();
    
    echo "<div style='padding: 1rem; background: linear-gradient(135deg, #f3e5f5 0%, #ffffff 100%); border-left: 4px solid #7b1fa2; border-radius: 8px;'>";
    echo "<strong style='font-size: 1.1em;'>🍽️ Total Menu</strong><br><br>";
    echo "📋 Total Menu: <strong>" . $row['total'] . "</strong><br>";
    echo "✅ Tersedia: <strong>" . $row['available'] . "</strong><br>";
    echo "❌ Habis: <strong>" . $row['out_of_stock'] . "</strong>";
    echo "</div>";

} elseif (strpos($message, 'performa') !== false || strpos($message, 'ringkasan') !== false || strpos($message, 'summary') !== false || strpos($message, 'overview') !== false) {
    // Business Overview
    $today = date('Y-m-d');
    
    // Today's revenue
    $revenueQuery = "SELECT SUM(total_bayar) as revenue, COUNT(*) as trans_count FROM transaksi WHERE DATE(created_at) = '$today'";
    $revenueResult = $conn->query($revenueQuery);
    $revenueRow = $revenueResult->fetch_assoc();
    
    // Best seller
    $bestQuery = "SELECT m.nama, SUM(dt.jumlah) as sold FROM detail_transaksi dt 
                  JOIN menu m ON dt.menu_id = m.id 
                  JOIN transaksi t ON dt.transaksi_id = t.id
                  WHERE DATE(t.created_at) = '$today'
                  GROUP BY dt.menu_id ORDER BY sold DESC LIMIT 1";
    $bestResult = $conn->query($bestQuery);
    $bestRow = $bestResult->fetch_assoc();
    
    // Low stock count
    $stockQuery = "SELECT COUNT(*) as low_stock FROM bahan_baku WHERE stok <= 10";
    $stockResult = $conn->query($stockQuery);
    $stockRow = $stockResult->fetch_assoc();
    
    echo "<div style='padding: 1rem; background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%); border-left: 4px solid #FFCC00; border-radius: 8px;'>";
    echo "<strong style='font-size: 1.2em;'>📊 Ringkasan Bisnis Hari Ini</strong><br>";
    echo "<span style='color: #6c757d; font-size: 0.9em;'>" . date('d M Y') . "</span><br><br>";
    
    echo "💰 <strong>Penghasilan:</strong> Rp " . number_format($revenueRow['revenue'] ?? 0, 0, ',', '.') . "<br>";
    echo "📝 <strong>Transaksi:</strong> " . ($revenueRow['trans_count'] ?? 0) . " transaksi<br>";
    
    if ($bestRow) {
        echo "🏆 <strong>Terlaris:</strong> " . $bestRow['nama'] . " (" . $bestRow['sold'] . " porsi)<br>";
    }
    
    if ($stockRow['low_stock'] > 0) {
        echo "⚠️ <strong>Perhatian:</strong> " . $stockRow['low_stock'] . " bahan stok menipis<br>";
    } else {
        echo "✅ <strong>Stok:</strong> Semua aman<br>";
    }
    
    echo "</div>";

} else {
    // Default response
    echo "🤔 Hmm, saya kurang paham maksud Anda.<br><br>";
    echo "Coba tanyakan:<br>";
    echo "• \"<strong>Penghasilan hari ini</strong>\"<br>";
    echo "• \"<strong>Menu terlaris</strong>\"<br>";
    echo "• \"<strong>Stok menipis</strong>\"<br>";
    echo "• \"<strong>Ringkasan bisnis</strong>\"<br>";
    echo "• Atau ketik \"<strong>help</strong>\" untuk bantuan lengkap";
}
?>