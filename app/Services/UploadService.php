<?php
/**
 * TrackXa - Secure Upload Service
 */
class UploadService {

    public static function image(array $file, string $directory = 'uploads'): string|false {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        if ($file['size'] > UPLOAD_MAX_SIZE) return false;

        // Validate MIME type using finfo (not trusting $_FILES['type'])
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!in_array($mime, ALLOWED_IMG_TYPES)) return false;

        $ext   = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => false,
        };
        if (!$ext) return false;

        $dir  = PUBLIC_PATH . '/images/' . $directory;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $name = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest = $dir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

        return 'images/' . $directory . '/' . $name;
    }

    public static function delete(string $path): void {
        $full = PUBLIC_PATH . '/' . $path;
        if (file_exists($full) && is_file($full)) {
            @unlink($full);
        }
    }
}
