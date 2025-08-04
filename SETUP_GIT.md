# Git Setup Instructions for COVID-19 Project

## 🚀 Quick Git Upload Guide

### Step 1: Install Git
1. Download Git from: https://git-scm.com/download/windows
2. Install with default settings
3. Verify installation: `git --version`

### Step 2: Create GitHub Repository
1. Go to https://github.com
2. Click "New Repository"
3. Repository name: `covid-information-system`
4. Description: `COVID-19 Information Management System`
5. Set to Public/Private as preferred
6. ✅ Check "Add a README file"
7. Click "Create repository"

### Step 3: Configure Git Identity (REQUIRED - Fix Your Current Issue)
```bash
# IMPORTANT: Your actual information
git config --global user.name "Shariq1311"
git config --global user.email "shariqbhatty@gmail.com"

# Verify configuration
git config --global user.name
git config --global user.email
```

### Step 4: EXACT COMMANDS FOR YOU TO RUN NOW

```bash
# Navigate to your project directory
cd c:\xampp\htdocs\E-project-Covid\Eproject\covid-master\

# Create and switch to main branch
git checkout -b main

# Add all files
git add .

# Commit with your configured identity
git commit -m "Initial commit: COVID-19 Information System"

# Update remote URL with your actual GitHub username
git remote set-url origin https://github.com/Shariq1311/covid-information-system.git

# Push to GitHub
git push -u origin main
```

### Step 5: If Remote Doesn't Exist Yet
```bash
# If you get "No such remote 'origin'" error, run this first:
git remote add origin https://github.com/Shariq1311/covid-information-system.git

# Then push
git push -u origin main
```

## 🔧 Git Workflow for Updates

### Making Changes
```bash
# Check current status
git status

# Add modified files
git add .

# Commit changes
git commit -m "Update: Brief description of changes"

# Push to GitHub
git push
```

### Creating Branches for Features
```bash
# Create and switch to new branch
git checkout -b feature/user-profile-update

# Make changes, then commit
git add .
git commit -m "Add user profile update functionality"

# Push branch to GitHub
git push origin feature/user-profile-update

# Switch back to main branch
git checkout main

# Merge feature branch (after testing)
git merge feature/user-profile-update
```

## 📁 Files to Include/Exclude

### ✅ Include These Files:
- All PHP files (*.php)
- HTML templates
- CSS stylesheets (/css/)
- JavaScript files (/js/)
- Images and assets (/images/)
- Documentation (/docs/)
- Database schema (without data)
- Configuration examples
- README and setup files

### ❌ Exclude These Files (via .gitignore):
- Database configuration with credentials
- Log files (*.log)
- User uploaded files (/uploads/*)
- Cache files
- IDE settings
- Sensitive configuration files

## 🔒 Security Considerations

### Before Uploading:
1. **Remove Database Credentials**
   ```php
   // Create config/database.example.php instead
   $host = "localhost";
   $username = "your_username";
   $password = "your_password";
   $database = "your_database";
   ```

2. **Check for Hardcoded Secrets**
   - API keys
   - Passwords
   - Database credentials
   - Server paths

3. **Create Environment Example**
   ```bash
   # Create .env.example
   DB_HOST=localhost
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   DB_NAME=covid_db
   ```

## 📊 Repository Setup Options

### Option 1: Public Repository
**Pros:**
- Showcases your work
- Good for portfolio
- Others can contribute

**Cons:**
- Code is visible to everyone
- Need to be extra careful with security

### Option 2: Private Repository
**Pros:**
- Code remains private
- Better for academic projects
- Full control over access

**Cons:**
- Limited collaboration
- Not visible for portfolio

## 🚀 Advanced Git Commands

### Useful Commands
```bash
# View commit history
git log --oneline

# Check differences
git diff

# Undo last commit (keep changes)
git reset HEAD~1

# Create release tag
git tag -a v1.0 -m "Release version 1.0"
git push origin v1.0

# Clone repository to new location
git clone https://github.com/USERNAME/covid-information-system.git
```

### Collaboration Commands
```bash
# Add collaborator's remote
git remote add collaborator https://github.com/THEIR_USERNAME/covid-information-system.git

# Fetch changes from collaborator
git fetch collaborator

# Merge collaborator's changes
git merge collaborator/main
```

## 📋 Pre-Upload Checklist

- [ ] Git installed and configured
- [ ] GitHub repository created
- [ ] .gitignore file in place
- [ ] Sensitive files removed/hidden
- [ ] README.md completed
- [ ] Code tested and working
- [ ] Documentation updated
- [ ] Database schema exported (without sensitive data)
- [ ] Example configuration files created

## 🆘 Troubleshooting

### Common Issues:

**1. Authentication Failed**
```bash
# Use GitHub Personal Access Token instead of password
# Generate token at: Settings > Developer settings > Personal access tokens
```

**2. Large File Error**
```bash
# Remove large files from git history
git filter-branch --force --index-filter 'git rm --cached --ignore-unmatch large-file.sql' --prune-empty --tag-name-filter cat -- --all
```

**3. Permission Denied**
```bash
# Check remote URL
git remote -v

# Update remote URL if needed
git remote set-url origin https://github.com/USERNAME/repository.git
```

## 🆘 Troubleshooting Your Specific Issues

### Issue 1: "Please tell me who you are"
**Problem:** Git doesn't know your identity
**Solution:**
```bash
# Set your identity globally
git config --global user.name "Your Full Name"
git config --global user.email "your.email@example.com"

# Verify it's set
git config --list | grep user
```

### Issue 2: "src refspec main does not match any"
**Problem:** No commits exist or wrong branch name
**Solution:**
```bash
# Check if you have any commits
git log --oneline

# If no commits, make sure files are added and committed
git add .
git commit -m "Initial commit"

# Check current branch name
git branch

# If branch is not 'main', either rename it or use the correct name
git branch -M main  # Rename current branch to main
```

### Issue 3: "remote origin already exists"
**Problem:** Remote URL already configured
**Solution:**
```bash
# Check current remote
git remote -v

# Update the URL with your actual GitHub username
git remote set-url origin https://github.com/YOUR_ACTUAL_USERNAME/covid-information-system.git

# Verify the change
git remote -v
```

### Issue 4: Authentication Problems
**Problem:** GitHub authentication failing
**Solution:**
```bash
# Use Personal Access Token instead of password
# 1. Go to GitHub.com → Settings → Developer settings → Personal access tokens
# 2. Generate new token with 'repo' permissions
# 3. Use token as password when prompted

# Or configure credential helper
git config --global credential.helper manager-core
```

## 📋 Step-by-Step Fix for Your Current Situation

### Execute These Commands in Order:
```bash
# 1. Configure your Git identity
git config --global user.name "Shari"
git config --global user.email "shari@youremail.com"

# 2. Navigate to project directory
cd c:\xampp\htdocs\E-project-Covid\Eproject\covid-master\

# 3. Check status and fix branch
git status
git branch
git checkout -b main  # Create main branch if it doesn't exist

# 4. Add files and commit
git add .
git commit -m "Initial commit: COVID-19 Information System"

# 5. Update remote URL (replace with your actual GitHub username)
git remote set-url origin https://github.com/YOUR_ACTUAL_USERNAME/covid-information-system.git

# 6. Push to GitHub
git push -u origin main
```

### What Each Command Does:
1. **Sets your Git identity** - Required for commits
2. **Navigates to project** - Ensures you're in right directory
3. **Creates main branch** - Standard default branch name
4. **Stages and commits files** - Prepares code for upload
5. **Sets correct remote URL** - Points to your GitHub repository
6. **Pushes code** - Uploads to GitHub

## 🔧 Alternative Method: GitHub Desktop

If command line continues to cause issues:

1. **Download GitHub Desktop**: https://desktop.github.com/
2. **Sign in** with your GitHub account
3. **Clone repository** from GitHub.com
4. **Copy your project files** to the cloned folder
5. **Commit and push** using the GUI

---

**Ready to upload your COVID-19 project to GitHub!** 🎉
