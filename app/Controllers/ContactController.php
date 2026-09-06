<?php
class ContactController extends Controller {
    public function index(array $params = []): void {
        $this->view('public.contact', [
            'title' => 'Contact Us – ' . Settings::get('site_name', 'TrackXa'),
            'success' => Session::getInstance()->getFlash('contact_success'),
            'error'   => Session::getInstance()->getFlash('contact_error'),
        ]);
    }

    public function send(array $params = []): void {
        $this->validateCsrf();

        if (!Security::rateLimit('contact_' . Security::ip(), 3, 300)) {
            Session::getInstance()->flash('contact_error', 'Too many requests. Please wait a few minutes.');
            $this->redirect(BASE_URL . '/contact');
        }

        $name    = $this->post('name', '');
        $email   = $this->post('email', '');
        $subject = $this->post('subject', '');
        $message = $this->post('message', '');

        if (empty($name) || empty($email) || empty($message)) {
            Session::getInstance()->flash('contact_error', 'Please fill in all required fields.');
            $this->redirect(BASE_URL . '/contact');
        }

        if (!Security::validateEmail($email)) {
            Session::getInstance()->flash('contact_error', 'Please enter a valid email address.');
            $this->redirect(BASE_URL . '/contact');
        }

        Database::getInstance()->prepare(
            "INSERT INTO contact_messages (name,email,subject,message,ip_address) VALUES (?,?,?,?,?)"
        )->execute([$name, $email, $subject, $message, Security::ip()]);

        // Email admin
        $adminEmail = Settings::get('site_email', '');
        if ($adminEmail) {
            $body = "<p><strong>From:</strong> {$name} ({$email})</p>
                     <p><strong>Subject:</strong> {$subject}</p>
                     <p><strong>Message:</strong><br>" . nl2br(Security::e($message)) . "</p>";
            EmailService::send($adminEmail, "New Contact: {$subject}", $body);
        }

        Session::getInstance()->flash('contact_success', 'Your message has been sent. We will get back to you shortly.');
        $this->redirect(BASE_URL . '/contact');
    }
}
