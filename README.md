# Quiz Battle Arena

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Real-time 1v1 Quiz Battle System built with Laravel 12, Reverb WebSockets, and Anti-cheat Protection</strong>
</p>

## 🎯 About Quiz Battle Arena

Quiz Battle Arena is a modern, real-time quiz competition platform where players can engage in 1v1 battles across multiple categories. Built with Laravel 12 and featuring real-time WebSocket communication, anti-cheat protection, and a beautiful responsive interface.

### ✨ Key Features

-   **🏆 Real-time 1v1 Quiz Battles** - Compete against opponents in live quiz matches
-   **📚 Multiple Categories** - Science, History, Geography, Sports, Entertainment, Technology, Literature, Art, Music, Mathematics
-   **⚡ Smart Matchmaking** - Automatic opponent finding with 30-second timeout
-   **🔗 Direct Join with Codes** - Share 8-character quiz codes for direct battles
-   **🛡️ Anti-cheat Protection** - Tab switching, browser closing, and navigation detection
-   **📱 Responsive Design** - Beautiful Tailwind CSS interface with dark mode support
-   **🚀 Real-time Updates** - Live scoring, timers, and battle events via WebSockets

## 🏗️ System Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Categories    │    │  Matchmaking    │    │   Battle        │
│   (Browse)      │───▶│   (30s timer)   │───▶│   (Real-time)   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
        │                       │                       │
        ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Join with Code  │    │  Redis Queue    │    │  WebSocket      │
│ (Direct entry)  │    │  Management     │    │  Broadcasting   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🚀 Tech Stack

-   **Backend**: Laravel 12, PHP 8.3+
-   **Database**: PostgreSQL with Redis for caching and sessions
-   **Real-time**: Laravel Reverb (WebSocket server)
-   **Frontend**: Blade templates + Tailwind CSS 4 + jQuery
-   **Testing**: Pest PHP with 12 comprehensive tests
-   **Anti-cheat**: JavaScript-based monitoring and server validation

## 📦 Installation

### Prerequisites

-   PHP 8.3 or higher
-   Composer
-   Node.js & NPM
-   PostgreSQL
-   Redis

### Setup Steps

1. **Clone & Install Dependencies**

    ```bash
    git clone <repository-url> quiz-app
    cd quiz-app
    composer install
    npm install
    ```

2. **Environment Configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Database Setup**

    ```bash
    # Configure database in .env
    php artisan migrate:fresh --seed
    ```

4. **Build Assets**

    ```bash
    npm run build
    ```

5. **Start Servers**

    ```bash
    # Terminal 1 - Laravel Server
    php artisan serve --host=0.0.0.0 --port=8000

    # Terminal 2 - WebSocket Server
    php artisan reverb:start
    ```

## 🎮 How to Play

1. **Choose Category** - Select from 10 quiz categories on the homepage
2. **Find Opponent** - Wait up to 30 seconds for automatic matchmaking
3. **Battle!** - Answer questions quickly and correctly to win
4. **Win Conditions** - Most correct answers wins; ties broken by fastest total time

### Alternative: Join with Code

-   Click "Join with Code" and enter an 8-character quiz code for direct battles

## 🛡️ Anti-Cheat System

The system includes comprehensive anti-cheat protection:

-   **Tab Switching Detection** - Automatic loss if player switches tabs
-   **Browser Close Protection** - Immediate loss if browser/tab is closed
-   **Navigation Guards** - Confirmation dialogs prevent accidental navigation
-   **Real-time Monitoring** - Server-side validation of all player actions

## 🧪 Testing

Run the comprehensive test suite:

```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/QuizBattleTest.php

# With coverage (if configured)
php artisan test --coverage
```

**Test Coverage**: 12 tests covering all major functionality:

-   ✅ Category browsing and selection
-   ✅ Matchmaking flow and validation
-   ✅ Battle creation and management
-   ✅ Quiz code generation and joining
-   ✅ Service layer functionality
-   ✅ Model relationships and data integrity

## 🗂️ Database Schema

### Core Tables

-   **categories** - Quiz categories (Science, History, etc.)
-   **quizzes** - Quiz instances with unique 8-character codes
-   **questions** - Quiz questions linked to quizzes
-   **question_options** - Multiple choice options with correct answer flags

### Battle State (Redis)

-   **matchmaking:categoryId** - Player queues for each category
-   **battle:battleId** - Real-time battle state and player data

## 🌐 API Endpoints

### Web Routes

-   `GET /` - Homepage with categories
-   `GET /join` - Join with quiz code
-   `POST /matchmaking/start` - Start matchmaking
-   `GET /battle/{battleId}` - Battle interface

### API Routes (AJAX)

-   `POST /api/battle/{battleId}/join` - Join specific battle
-   `POST /api/battle/{battleId}/submit-score` - Submit final score
-   `POST /api/battle/{battleId}/auto-lose` - Handle anti-cheat violations

## 📊 Real-time Events

### WebSocket Channels

-   `private-player.{sessionToken}` - Individual player notifications
-   `private-battle.{battleId}` - Battle-specific events

### Broadcast Events

-   `MatchFound` - Opponent found, battle created
-   `BattleStarted` - Quiz questions loaded, timer started
-   `PlayerJoined` - Player joined battle
-   `BattleEnded` - Final results and winner announcement

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=quiz_app

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
CACHE_STORE=redis
SESSION_DRIVER=redis

# Broadcasting (Reverb)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=465941
REVERB_APP_KEY=your-key
REVERB_APP_SECRET=your-secret
REVERB_HOST=localhost
REVERB_PORT=8080
```

## 🚀 Deployment

For production deployment:

1. **Optimize Application**

    ```bash
    php artisan optimize
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

2. **Queue Workers**

    ```bash
    php artisan queue:work --daemon
    ```

3. **Scheduled Tasks**

    ```bash
    * * * * * php artisan schedule:run >> /dev/null 2>&1
    ```

4. **WebSocket Server**
    - Use process manager (PM2, Supervisor) for Reverb
    - Configure reverse proxy (Nginx) for WebSocket connections

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

-   Follow Laravel coding standards
-   Write tests for new features
-   Update documentation as needed
-   Ensure anti-cheat measures aren't compromised

## 📝 License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

-   **Laravel Framework** - Elegant PHP framework
-   **Laravel Reverb** - Real-time WebSocket server
-   **Tailwind CSS** - Utility-first CSS framework
-   **Pest PHP** - Elegant testing framework
