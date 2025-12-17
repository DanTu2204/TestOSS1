<?php

// 1. Khai báo danh sách $n câu hỏi trắc nghiệm (N=8 trong ví dụ này)
$questions = [
    1 => [
        'question' => 'Thủ đô của nước Cộng hòa Xã hội Chủ nghĩa Việt Nam là gì?',
        'options' => ['A. TP. Hồ Chí Minh', 'B. Đà Nẵng', 'C. Hà Nội', 'D. Cần Thơ'],
        'answer' => 'C. Hà Nội'
    ],
    2 => [
        'question' => 'Ngôn ngữ lập trình PHP được phát triển bởi ai?',
        'options' => ['A. Linus Torvalds', 'B. Rasmus Lerdorf', 'C. James Gosling', 'D. Guido van Rossum'],
        'answer' => 'B. Rasmus Lerdorf'
    ],
    3 => [
        'question' => 'Năm 1945, sự kiện lịch sử quan trọng nào đã diễn ra tại Việt Nam?',
        'options' => ['A. Chiến dịch Điện Biên Phủ', 'B. Cách mạng Tháng Tám', 'C. Thành lập Đảng Cộng sản', 'D. Hiệp định Genève'],
        'answer' => 'B. Cách mạng Tháng Tám'
    ],
    4 => [
        'question' => 'Thiên thể nào được gọi là "hành tinh đỏ"?',
        'options' => ['A. Sao Thủy', 'B. Sao Kim', 'C. Sao Hỏa', 'D. Sao Mộc'],
        'answer' => 'C. Sao Hỏa'
    ],
    5 => [
        'question' => 'Cấu trúc điều khiển nào được sử dụng để lặp lại một khối mã khi điều kiện còn đúng trong PHP?',
        'options' => ['A. for', 'B. if-else', 'C. while', 'D. switch'],
        'answer' => 'C. while'
    ],
    6 => [
        'question' => 'Đơn vị tiền tệ chính thức của Nhật Bản là gì?',
        'options' => ['A. Won', 'B. Nhân dân tệ', 'C. Yên', 'D. Đô la'],
        'answer' => 'C. Yên'
    ],
    7 => [
        'question' => 'Khái niệm nào mô tả việc đóng gói dữ liệu và các phương thức xử lý dữ liệu đó thành một đơn vị duy nhất trong Lập trình hướng đối tượng (OOP)?',
        'options' => ['A. Kế thừa (Inheritance)', 'B. Đa hình (Polymorphism)', 'C. Đóng gói (Encapsulation)', 'D. Trừu tượng (Abstraction)'],
        'answer' => 'C. Đóng gói (Encapsulation)'
    ],
    8 => [
        'question' => 'Biểu tượng hóa học của vàng là gì?',
        'options' => ['A. Ag', 'B. Fe', 'C. Au', 'D. Cu'],
        'answer' => 'C. Au'
    ],
];

// 2. Định nghĩa số lượng câu hỏi ban đầu (n) và số lượng câu hỏi ngẫu nhiên cần lấy (m)
$n = count($questions); // Số lượng câu hỏi ban đầu (8)
$m = 5; // Số lượng câu hỏi cần lấy ngẫu nhiên cho đề thi (m < n)

// Kiểm tra điều kiện: m phải nhỏ hơn n
if ($m >= $n) {
    die("Lỗi: Số lượng câu hỏi cần lấy (m = $m) phải nhỏ hơn tổng số câu hỏi (n = $n).");
}

// 3. Lấy ngẫu nhiên $m khóa (keys) từ mảng $questions
// array_rand(array $array, int $num) trả về một mảng chứa $num khóa ngẫu nhiên.
$random_keys = array_rand($questions, $m);

// 4. Xây dựng mảng đề thi từ các khóa ngẫu nhiên
$exam_questions = [];
foreach ($random_keys as $key) {
    $exam_questions[$key] = $questions[$key];
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đề Thi Trắc Nghiệm Ngẫu Nhiên (<?php echo $m; ?> Câu)</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .exam-title { text-align: center; color: #0056b3; }
        .question-item { margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 5px; }
        .question-text { font-weight: bold; margin-bottom: 10px; color: #333; }
        .options-list { list-style-type: none; padding-left: 0; }
        .options-list li { margin-bottom: 5px; }
    </style>
</head>
<body>

    <h1 class="exam-title">ĐỀ THI TRẮC NGHIỆM TỔNG HỢP</h1>
    <h3 class="exam-title">Số lượng câu hỏi: <?php echo $m; ?> / <?php echo $n; ?> (Lấy ngẫu nhiên)</h3>

    <?php 
    $stt = 0;
    foreach ($exam_questions as $question_data) {
        $stt++;
        echo '<div class="question-item">';
        echo '<p class="question-text">Câu ' . $stt . '. ' . $question_data['question'] . '</p>';
        echo '<ul class="options-list">';
        
        // In ra các phương án lựa chọn
        foreach ($question_data['options'] as $option) {
            echo '<li>' . $option . '</li>';
        }
        
        echo '</ul>';
        // Có thể ẩn đáp án trong trang thi thực tế, nhưng giữ lại cho mục đích kiểm tra
        // echo '<p style="color: green; font-style: italic;">(Đáp án: ' . $question_data['answer'] . ')</p>';
        echo '</div>';
    }
    ?>

</body>
</html>