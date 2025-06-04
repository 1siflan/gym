<?php
/**
 * PowerGym - Main Entry Point
 * 
 * @category Frontend
 * @package  PowerGym
 */

// Strict types for type safety
declare(strict_types=1);

// Environment setup
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

// SEO Meta Variables
$pageTitle = "PowerGym - Transform Your Body";
$pageDescription = "Premium fitness center with personalized training programs";

// HTTP Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Check for maintenance mode
if (file_exists(__DIR__ . '/maintenance.flag')) {
    header('HTTP/1.1 503 Service Unavailable');
    include __DIR__ . '/maintenance.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="/assets/css/main.css" as="style">
    <link rel="preload" href="/assets/js/main.js" as="script">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/images/logo.svg">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="/site.webmanifest">
    
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <!-- Inline Critical CSS -->
    <style>
        :root {
            --primary: #3a86ff;
            --dark: #2b2d42;
        }
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('/assets/images/gym-bg.jpg') center/cover;
        }
    </style>
</head>
<body>
    <!-- Skip Navigation -->
    <a href="#main-content" class="skip-nav">Skip to content</a>
    
    <!-- Header Partial -->
    <?php include_once __DIR__ . '/includes/header.php'; ?>
    
    <main id="main-content">
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="text-3d">Transform Your Body</h1>
                    <p class="lead">Join <?= getTotalMembers($conn) ?>+ members achieving their fitness goals</p>
                    
                    <div class="cta-group">
                        <?php if (isLoggedIn()): ?>
                            <a href="/members/dashboard.php" class="btn btn-primary btn-lg">
                                My Dashboard
                            </a>
                        <?php else: ?>
                            <a href="/register.php" class="btn btn-primary btn-lg">
                                Start Your Journey
                            </a>
                            <a href="/login.php" class="btn btn-outline-light btn-lg">
                                Member Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Dynamic Class Schedule -->
        <section class="py-5">
            <div class="container">
                <h2 class="section-title">Today's Classes</h2>
                <div class="schedule-grid" id="schedule-container">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
        </section>
        
        <!-- Real-time Member Counter -->
        <section class="counter-section bg-dark text-white">
            <div class="container">
                <div class="counter-item">
                    <span class="counter" data-target="<?= getActiveMembersCount($conn) ?>">0</span>
                    <span>Active Members</span>
                </div>
                <div class="counter-item">
                    <span class="counter" data-target="<?= getTrainersCount($conn) ?>">0</span>
                    <span>Certified Trainers</span>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Footer Partial -->
    <?php include_once __DIR__ . '/includes/footer.php'; ?>
    
    <!-- Lazy-loaded JS -->
    <script src="/assets/js/main.js" defer></script>
    
    <!-- Inline JS for Critical Functionality -->
    <script>
        // Progressive enhancement
        document.addEventListener('DOMContentLoaded', () => {
            // Load schedule via AJAX
            fetch('/api/get_schedule.php?day=<?= date('l') ?>')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('schedule-container');
                    container.innerHTML = data.map(classItem => `
                        <div class="schedule-card">
                            <h3>${classItem.name}</h3>
                            <p>${classItem.time} with ${classItem.trainer}</p>
                        </div>
                    `).join('');
                });
            
            // Animate counters
            animateCounters();
        });
        
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const increment = target / speed;
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 1);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
            });
        }
    </script>
</body>
</html>