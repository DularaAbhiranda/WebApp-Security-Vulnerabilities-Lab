# 🔐 WebApp-Security-Vulnerabilities-Lab

This repository demonstrates **five critical web application security vulnerabilities** with both vulnerable and secure implementations. Each example is designed to provide hands-on experience in understanding, exploiting, and mitigating real-world security flaws across multiple technologies.

---

## 📚 Project Overview

This project includes the following vulnerabilities:

| #  | Vulnerability                | Language/Tech Stack | Exploitation Method                 | Secure Fix Included |
|----|------------------------------|---------------------|-------------------------------------|---------------------|
| 1  | SQL Injection (SQLi)         | PHP + MySQL         | Injection via login forms           | ✅ Prepared Statements |
| 2  | Buffer Overflow              | C (CLI app)         | Stack overflow with unchecked input | ✅ Safe input handling |
| 3  | Cross-Site Scripting (XSS)   | PHP + HTML          | JavaScript injection in comments    | ✅ Output encoding + CSP |
| 4  | Java Deserialization         | Java + Tomcat       | Remote code execution via payload   | ✅ Class whitelisting + JSON |
| 5  | Broken Authentication (OAuth2 misuse) | PHP OAuth Client | Session fixation, CSRF             | ✅ State + PKCE + validation |

---

## 🧰 Technologies Used

- PHP 8.1
- MySQL 5.7
- Apache (XAMPP/LAMP)
- C (GCC on Linux)
- Java (Servlets on Apache Tomcat)
- Apache Commons Collections
- Burp Suite
- Valgrind
- HTML, CSP, JSON

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/DularaAbhiranda/WebApp-Security-Vulnerabilities-Lab.git
cd WebApp-Security-Vulnerabilities-Lab
````


2. Explore Vulnerabilities
Each folder contains:

vulnerable/ – Insecure version

secure/ – Fixed secure version

README.md – Instructions and test cases

Example:

```
cd sql-injection/vulnerable
3. Run the Code
Use XAMPP or LAMP for PHP-based systems
```

Compile C code with:
```
gcc -o demo buffer_overflow.c
./demo
```

Deploy Java projects using Apache Tomcat

Test XSS and OAuth flows using modern browsers or Burp Suite

🧠 What You'll Learn
How security flaws are introduced in code

How to simulate exploitation techniques

How to fix vulnerabilities using secure coding

The importance of validation, encoding, and safe design

Defense-in-depth and SDL-based thinking



🧑‍💻 Author
Dulara Abhiranda
GitHub: github.com/DularaAbhiranda

📖 References
OWASP Top 10 (2024)

SEI CERT C/C++ Standards

NIST Secure Software Development Framework

OAuth 2.0 RFC 6749

OWASP Cheat Sheet Series

⚠️ Disclaimer
This project is intended for educational and ethical testing purposes only. Do not deploy any of the vulnerable systems in a production environment. Use responsibly.

⭐ Contribute
Feel free to fork, improve, or ask questions via GitHub Issues.







