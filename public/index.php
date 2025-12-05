<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <title>
        StudyShare - Ứng dụng chia sẻ tài liệu học tập cho học sinh,
        sinh
        viên
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="../assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml"
        href="../assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="../assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180"
        href="../assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="../assets/favicon/site.webmanifest" />

    <!-- Embed Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com"
        crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Font awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <!-- Style CSS -->
    <link rel="stylesheet" href="../assets/css/index.css" />
    <link rel="stylesheet" href="./assets/css/landing.css" />
</head>

<body>
    <!-- Header -->
    <header class="header fixed">
        <div class="container">
            <div class="header__inner">
                <!-- Logo -->
                <a href="#" class="logo">
                    <img src="../assets/icons/logo.svg"
                        alt="Study Share" />
                    <span>StudyShare</span>
                </a>

                <!-- Navigation -->
                <nav class="navbar">
                    <ul class="navbar__list">
                        <li class="navbar__item">
                            <a href="#" class="navbar__link">Trang
                                chủ</a>
                        </li>
                        <li class="navbar__item">
                            <a href="#features"
                                class="navbar__link">Tính năng</a>
                        </li>
                        <li class="navbar__item">
                            <a href="#benefit"
                                class="navbar__link">Lợi ích</a>
                        </li>
                        <li class="navbar__item">
                            <a href="#who" class="navbar__link">Dành
                                cho ai?</a>
                        </li>
                        <li class="navbar__item">
                            <a href="#faq"
                                class="navbar__link">FAQ</a>
                        </li>
                    </ul>
                </nav>

                <!-- Actions -->
                <div class="actions">
                    <a href="../app/pages/auth/register.php"
                        class="actions__link">Đăng ký</a>
                    <a href="../app/pages/auth/login.php"
                        class="actions__btn btn">Đăng nhập</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="main">
        <!-- Hero -->
        <section class="hero">
            <div class="container">
                <div class="hero__inner">
                    <!-- Hero Left -->
                    <div class="hero__left">
                        <h1 class="hero__heading">
                            Chia sẻ tài liệu, kết nối tri thức
                        </h1>
                        <p class="hero__desc">
                            StudyShare là nền tảng chia sẻ tài liệu
                            học tập
                            hàng đầu dành cho học sinh và sinh viên.
                            Tìm
                            kiếm slide bài giảng, đề cương ôn thi, đề
                            kiểm
                            tra, ghi chú và nhiều tài liệu hữu ích
                            khác -
                            tất cả đến từ cộng đồng học tập nhiệt
                            huyết.
                        </p>

                        <div class="hero__cta">
                            <a href="#!" class="btn">Bắt đầu ngay</a>
                            <a href="" class="hero__link">Xem tài liệu
                                nổi bật</a>
                        </div>

                        <p class="hero__desc">
                            Miễn phí cho học sinh, sinh viên. Chỉ mất
                            vài
                            giây để bắt đầu.
                        </p>
                    </div>

                    <!-- Hero Right -->
                    <div class="hero__right">
                        <img class="hero__img"
                            src="../assets/images/hero.jpg" alt="" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="features">
            <div class="container">
                <div class="features__inner">
                    <h2 class="section-heading features__heading">
                        Tính năng nổi bật
                    </h2>
                    <!-- Feature List -->
                    <div class="features__list">
                        <!-- Item 1 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i class="fa-solid fa-download"></i>
                            </div>
                            <h3 class="features-item__title">
                                Chia sẻ tài liệu dễ dàng
                            </h3>
                            <p class="features-item__desc">
                                Upload file nhanh chóng, thêm mô tả và
                                chọn
                                môn học. Chia sẻ kiến thức chỉ trong
                                vài
                                bước đơn giản.
                            </p>
                        </div>

                        <!-- Item 2 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i
                                    class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <h3 class="features-item__title">
                                Tìm kiếm thông minh
                            </h3>
                            <p class="features-item__desc">
                                Tìm tài liệu theo tên, môn học,
                                trường, từ
                                khóa. Hệ thống lọc mạnh mẽ giúp bạn
                                tìm đúng
                                những gì cần.
                            </p>
                        </div>

                        <!-- Item 3 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <h3 class="features-item__title">
                                Đánh giá & bình luận
                            </h3>
                            <p class="features-item__desc">
                                Đánh giá chất lượng tài liệu, để lại
                                bình
                                luận và cảm ơn người chia sẻ. Xây dựng
                                cộng
                                đồng tích cực.
                            </p>
                        </div>

                        <!-- Item 4 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                            <h3 class="features-item__title">
                                Lưu tài liệu yêu thích
                            </h3>
                            <p class="features-item__desc">
                                Lưu các tài liệu quan trọng vào thư
                                viện cá
                                nhân để dễ dàng truy cập và ôn tập bất
                                cứ
                                lúc nào.
                            </p>
                        </div>

                        <!-- Item 5 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i class="fa-regular fa-file"></i>
                            </div>
                            <h3 class="features-item__title">
                                Thống kê nổi bật
                            </h3>
                            <p class="features-item__desc">
                                Xem tài liệu được tải nhiều nhất, đánh
                                giá
                                cao nhất theo từng môn học và trường.
                            </p>
                        </div>

                        <!-- Item 6 -->
                        <div class="features-item">
                            <div class="features-item__icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <h3 class="features-item__title">
                                Hỗ trợ nhiều định dạng
                            </h3>
                            <p class="features-item__desc">
                                PDF, DOCX, PPTX, hình ảnh và nhiều
                                định dạng
                                khác. Tải lên và xem trước trực tiếp
                                trên
                                nền tảng.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefit -->
        <section id="benefit" class="benefit">
            <div class="container">
                <div class="benefit__inner">
                    <h2 class="section-heading">Lợi ích dành cho bạn
                    </h2>

                    <div class="benefit__list">
                        <!-- Item 1 -->
                        <div class="benefit__item">
                            <div class="benefit__icon">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="benefit__content">
                                <h3 class="benefit__title">
                                    Tiết kiệm thời gian tìm tài liệu
                                </h3>
                                <p class="benefit__desc">
                                    Không còn phải tìm kiếm khắp nơi,
                                    mọi
                                    tài liệu đều có sẵn tại một nơi.
                                </p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="benefit__item">
                            <div class="benefit__icon">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="benefit__content">
                                <h3 class="benefit__title">
                                    Học từ tài liệu chất lượng
                                </h3>
                                <p class="benefit__desc">
                                    Tài liệu được cộng đồng đánh giá
                                    và kiểm
                                    chứng, đảm bảo độ chính xác cao.
                                </p>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="benefit__item">
                            <div class="benefit__icon">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="benefit__content">
                                <h3 class="benefit__title">
                                    Cập nhật theo môn học, học kỳ
                                </h3>
                                <p class="benefit__desc">
                                    Tài liệu luôn được cập nhật mới
                                    nhất
                                    theo chương trình học và học kỳ.
                                </p>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="benefit__item">
                            <div class="benefit__icon">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="benefit__content">
                                <h3 class="benefit__title">
                                    Góp phần xây dựng kho tri thức
                                </h3>
                                <p class="benefit__desc">
                                    Mỗi tài liệu bạn chia sẻ đều giúp
                                    ích
                                    cho hàng ngàn bạn học khác.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- For Who -->
        <section id="who" class="who">
            <div class="container">
                <div class="who__inner">
                    <h2 class="section-heading">Dành cho ai?</h2>

                    <!-- Who list -->
                    <div class="who__list">
                        <!-- Item 1 -->
                        <div class="who__item">
                            <div class="who__icon">
                                <i class="fa-solid fa-school"></i>
                            </div>
                            <h3 class="who__title">Học sinh THPT</h3>
                            <p class="who__desc">
                                Tài liệu luyện thi THPT Quốc gia, đề
                                cương
                                ôn tập, đề kiểm tra các môn. Chuẩn bị
                                kỳ thi
                                một cách tốt nhất với tài liệu từ các
                                bạn
                                học sinh khác.
                            </p>
                        </div>

                        <!-- Item 1 -->
                        <div class="who__item">
                            <div class="who__icon">
                                <i
                                    class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h3 class="who__title">
                                Sinh viên đại học / Cao đẳng
                            </h3>
                            <p class="who__desc">
                                Slide bài giảng, tài liệu môn học
                                chuyên
                                ngành, đồ án mẫu, báo cáo thực tập.
                                Học từ
                                kinh nghiệm của anh chị khóa trên để
                                đạt kết
                                quả cao hơn.
                            </p>
                        </div>

                        <!-- Item 3 -->
                        <div class="who__item">
                            <div class="who__icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <h3 class="who__title">
                                Câu lạc bộ & Nhóm học tập
                            </h3>
                            <p class="who__desc">
                                Chia sẻ tài liệu nội bộ, tổ chức kho
                                tài
                                liệu cho nhóm, câu lạc bộ học thuật.
                                Xây
                                dựng cộng đồng chia sẻ tri thức mạnh
                                mẽ.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="faq">
            <div class="container">
                <div class="faq__inner">
                    <h2 class="section-heading">Câu hỏi thường gặp
                    </h2>
                    <!-- FAQ LIST -->
                    <div class="faq__list">
                        <!-- Item 1 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>StudyShare có miễn phí không?</h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Có! StudyShare hoàn toàn miễn phí
                                    cho
                                    học sinh và sinh viên. Bạn có thể
                                    tải
                                    lên, tải xuống và chia sẻ tài liệu
                                    không
                                    giới hạn mà không mất bất kỳ chi
                                    phí
                                    nào.
                                </p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>
                                    Tôi có thể chia sẻ những loại tài
                                    liệu
                                    nào?
                                </h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Bạn có thể chia sẻ các loại tài
                                    liệu học
                                    tập như: PDF, DOCX, PPTX, XLSX,
                                    hình ảnh
                                    (JPG, PNG), và nhiều định dạng
                                    khác. Tài
                                    liệu có thể là slide bài giảng, đề
                                    cương, đề thi, báo cáo, ghi chú cá
                                    nhân,
                                    v.v. Tuy nhiên, tài liệu phải liên
                                    quan
                                    đến học tập và không vi phạm bản
                                    quyền.
                                </p>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>
                                    Làm sao để tài liệu của tôi nhiều
                                    người
                                    xem hơn
                                </h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Để tài liệu của bạn được nhiều
                                    người
                                    quan tâm, hãy: (1) Đặt tên tài
                                    liệu rõ
                                    ràng, mô tả chi tiết nội dung; (2)
                                    Chọn
                                    đúng môn học, trường, và từ khóa;
                                    (3)
                                    Upload tài liệu chất lượng cao,
                                    được
                                    trình bày đẹp; (4) Chia sẻ link
                                    tài liệu
                                    trong các nhóm học tập của bạn.
                                    Tài liệu
                                    được đánh giá cao sẽ được ưu tiên
                                    hiển
                                    thị!
                                </p>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>Tài liệu có được kiểm duyệt không?
                                </h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Có! Mọi tài liệu đều trải qua quá
                                    trình
                                    kiểm duyệt tự động và thủ công để
                                    đảm
                                    bảo không vi phạm bản quyền, không
                                    chứa
                                    nội dung không phù hợp. Ngoài ra,
                                    người
                                    dùng có thể báo cáo tài liệu vi
                                    phạm để
                                    đội ngũ quản trị xem xét và xử lý
                                    kịp
                                    thời.
                                </p>
                            </div>
                        </div>

                        <!-- Item 5 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>
                                    Tôi có thể tải tài liệu về máy
                                    không?
                                </h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Có! Sau khi đăng nhập, bạn có thể
                                    tải
                                    xuống bất kỳ tài liệu nào trên nền
                                    tảng
                                    về máy tính hoặc điện thoại của
                                    mình.
                                    Tài liệu tải về sẽ giữ nguyên định
                                    dạng
                                    gốc để bạn dễ dàng sử dụng.
                                </p>
                            </div>
                        </div>

                        <!-- Item 6 -->
                        <div class="faq__item">
                            <div class="faq__question">
                                <h3>
                                    StudyShare có ứng dụng di động
                                    không
                                </h3>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="faq__answer">
                                <p>
                                    Hiện tại StudyShare mới tối ưu
                                    trên
                                    laptop và PC. Chúng tôi đang phát
                                    triển
                                    ứng dụng mobile riêng cho iOS và
                                    Android, dự kiến ra mắt trong thời
                                    gian
                                    tới.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container">
                <div class="cta__inner">
                    <h2 class="section-heading cta__heading">
                        Sẵn sàng chia sẻ tài liệu cùng StudyShare?
                    </h2>
                    <p class="cta__desc">
                        Tham gia cộng đồng học tập sôi động với hàng
                        ngàn
                        học sinh, sinh viên đang chia sẻ và học hỏi
                        mỗi
                        ngày. Bắt đầu ngay hôm nay!
                    </p>
                    <!-- Actions -->
                    <div class="actions">
                        <a href="../app/pages/auth/register.php"
                            class="actions__btn btn cta__btn">Đăng
                            ký</a>
                        <a href="../app/pages/auth/login.php"
                            class="actions__btn btn cta__btn">Đăng
                            nhập</a>
                    </div>
                    <p class="cta__desc">
                        Hoàn toàn miễn phí cho học sinh, sinh viên.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__inner">
                <!-- List -->
                <div class="footer__list">
                    <!-- Column 1 -->
                    <a href="#" class="logo">
                        <img src="../assets/icons/logo.svg"
                            alt="Study Share" />
                        <span>StudyShare</span>
                    </a>

                    <!-- Column 2 -->
                    <div class="footer-item">
                        <h3>BTL Phát triển ứng dụng Web</h3>
                        <ul class="footer-item__list">
                            <li>Nguyễn Tiến Khởi</li>
                            <li>Nguyễn Văn Chung</li>
                            <li>Nguyễn Anh Quân</li>
                            <li>Dương Trần Nhật Anh</li>
                            <li>Hoàng Mạnh Cường</li>
                        </ul>
                    </div>
                </div>
                <p class="footer__copyright">Copyright © 2025</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const faqItems = document.querySelectorAll(
            ".faq__item");

        faqItems.forEach((item) => {
            const question = item.querySelector(
                ".faq__question");
            const answer = item.querySelector(
                ".faq__answer");

            // đặt maxHeight ban đầu là 0
            answer.style.maxHeight = "0px";

            question.addEventListener("click",
                function() {
                    const isOpen =
                        item.classList.contains(
                            "faq__item--open");

                    // Nếu đang mở -> đóng lại
                    if (isOpen) {
                        item.classList.remove(
                            "faq__item--open");
                        answer.style.maxHeight =
                            "0px";
                        question.classList.remove(
                            "faq__question--active"
                            );
                    } else {
                        // Mở item này
                        item.classList.add(
                            "faq__item--open");
                        answer.style.maxHeight =
                            answer.scrollHeight +
                            "px";
                        question.classList.add(
                            "faq__question--active"
                            );
                    }
                });
        });
    });
    </script>
</body>

</html>