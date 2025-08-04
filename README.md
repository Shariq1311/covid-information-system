# COVID-19 Information Management System

A comprehensive web-based platform for COVID-19 information management, vaccination tracking, and health data visualization.

## 🎯 Project Overview

This system provides a centralized platform for:
- Real-time COVID-19 statistics tracking
- Vaccination record management
- Health guidelines and safety protocols
- User authentication and profile management
- Data analytics and reporting

## 🛠️ Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend:** PHP 8.x
- **Database:** MySQL 8.x
- **Server:** Apache (XAMPP)
- **Version Control:** Git

## 📋 Features

### Core Functionality
- [x] User Registration & Authentication
- [x] COVID-19 Statistics Dashboard
- [x] Vaccination Records Management
- [x] Health Information Center
- [x] Admin Panel
- [x] Responsive Design
- [x] Security Implementation

### Advanced Features
- [x] Data Visualization with Charts
- [x] Search and Filter Options
- [x] File Upload System
- [x] Session Management
- [x] Input Validation
- [x] SQL Injection Protection

## 🚀 Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Git
- Modern web browser

### Setup Instructions

1. **Clone the Repository**
```bash
git clone https://github.com/yourusername/covid-information-system.git
cd covid-information-system
```

2. **Start XAMPP Services**
- Start Apache
- Start MySQL

3. **Database Setup**
```bash
# Import database
mysql -u root -p < database/covid_db.sql
```

4. **Configuration**
```php
// Copy and configure database settings
cp config/database.example.php config/database.php
// Edit database credentials in config/database.php
```

5. **Access Application**
```
http://localhost/E-project-Covid/Eproject/covid-master/
```

## 📁 Project Structure

```
covid-master/
├── index.php              # Main entry point
├── config/                 # Configuration files
│   ├── database.php       # Database connection
│   └── config.php         # App configuration
├── includes/              # Reusable components
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── css/                   # Stylesheets
├── js/                    # JavaScript files
├── images/                # Image assets
├── uploads/               # User uploads
├── admin/                 # Admin panel
├── api/                   # API endpoints
├── database/              # Database files
├── docs/                  # Documentation
└── tests/                 # Test files
```

## 🔒 Security Features

- **SQL Injection Protection** - Prepared statements
- **XSS Prevention** - Input sanitization
- **CSRF Protection** - Token validation
- **Password Security** - bcrypt hashing
- **Session Security** - Secure session handling
- **File Upload Security** - Type and size validation

## 📊 Database Schema

### Key Tables
- `users` - User accounts and profiles
- `covid_stats` - COVID-19 statistical data
- `vaccinations` - Vaccination records
- `news` - News and updates
- `appointments` - Vaccination appointments

## 🧪 Testing

### Run Tests
```bash
# PHP Unit Tests
composer test

# Security Tests
npm run security-test

# Performance Tests
npm run performance-test
```

## 📈 Performance

- **Page Load Time:** < 2 seconds
- **Database Queries:** Optimized with indexing
- **Responsive Design:** Mobile-first approach
- **Browser Support:** Chrome, Firefox, Safari, Edge

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write unit tests for new features
- Update documentation
- Ensure security best practices

## 📝 API Documentation

### Authentication Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout

### Data Endpoints
- `GET /api/covid/stats` - COVID statistics
- `GET /api/vaccination/records` - Vaccination records
- `POST /api/vaccination/add` - Add vaccination record

## 🐛 Known Issues

- [ ] Mobile view optimization needed for charts
- [ ] Email notification system pending
- [ ] Advanced search filters in progress

## 🚀 Future Enhancements

- [ ] Mobile application development
- [ ] AI-powered health recommendations
- [ ] Integration with government health APIs
- [ ] Multi-language support
- [ ] Real-time notifications
- [ ] Advanced analytics dashboard

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **[Your Name]** - *Lead Developer* - [GitHub Profile](https://github.com/yourusername)
- **[Team Member 2]** - *Frontend Developer*
- **[Team Member 3]** - *Database Designer*

## 🙏 Acknowledgments

- Bootstrap team for the UI framework
- Chart.js for data visualization
- PHP community for excellent documentation
- [Institution Name] for project support

## 📞 Support

For support and questions:
- **Email:** your.email@example.com
- **Issues:** [GitHub Issues](https://github.com/yourusername/covid-information-system/issues)
- **Documentation:** [Project Docs](docs/)

## 📊 Project Stats

![GitHub repo size](https://img.shields.io/github/repo-size/yourusername/covid-information-system)
![GitHub contributors](https://img.shields.io/github/contributors/yourusername/covid-information-system)
![GitHub last commit](https://img.shields.io/github/last-commit/yourusername/covid-information-system)
![GitHub issues](https://img.shields.io/github/issues/yourusername/covid-information-system)

---

**Live Demo:** [Demo Link](http://your-demo-url.com)  
**Documentation:** [Full Documentation](docs/README.md)  
**Presentation:** [Project Presentation](docs/presentation-slides.md)
