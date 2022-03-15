<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'idfood_vn' );

/** Username của database */
define( 'DB_USER', 'root' );

/** Mật khẩu của database */
//define( 'DB_PASSWORD', 'Dig@311' );
define( 'DB_PASSWORD', '' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'kiA7)gWC_]~b0/CsXfdw:1PrwD(JYz3Og#8> EGBhNf?.R|]-NB-{j;QBtajF^J^' );
define( 'SECURE_AUTH_KEY',  'RSg8-r4pwXwRp4b,iY*owOQWvxrYkLtr|/DR4Ka?Ss[?Z&st[qPVF~169R~QOn.K' );
define( 'LOGGED_IN_KEY',    ';,0w)k]@TH)e,AH5br;z4_s[{Xo0+<M{^E[K+Et*Qy!:-]U*:4r7n|aX .QBsrlX' );
define( 'NONCE_KEY',        '#6E#A$[C8FC!hsCUha.%EXl4^qn-%T_9pt|o>nWm=y4Le0IN)CENo1YJ%RN=$gG8' );
define( 'AUTH_SALT',        'n1~o8@_7iE)XF7R43UWLYwP(4R+M$KZ#XZQFXi<w;[$PGK,9LaTvF#K%`2rTLzEU' );
define( 'SECURE_AUTH_SALT', ';iuuiP<o(R;[%FHc@Fx6E8m_6zq|!hwqaUi&35.[23SpQAM1gL4qIVF-d)pVlgIY' );
define( 'LOGGED_IN_SALT',   'b6BsF*[hlU/=W5QG-1.5Z-2,?pFZO^~[wdJB7Jj $B:7&Q$V+Z<4A<iw7 x2&6y|' );
define( 'NONCE_SALT',       'Q^S  xPcQn^f6xH)2{;FtcCSAx&+/4}o7v)mLDjHt%#EJ)DGMrR rb&>) pHM?_A' );
define('JWT_AUTH_SECRET_KEY', 'kiA7)gWC_]~b0/CsXfdw:1PrwD(JYz3Og#8> EGBhNf?.R|]-NB-{j;QBtaj^J^');

define('JWT_AUTH_CORS_ENABLE', true);

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'pte_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
