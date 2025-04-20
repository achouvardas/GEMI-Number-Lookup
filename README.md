# 📘 GEMI Number Lookup

A simple PHP-based web app that allows users to fetch and display detailed information about Greek companies using their **GEMI number**, via the official [publicity.businessportal.gr](https://publicity.businessportal.gr/) API.

---

## 🚀 Features

- 🔍 Search Greek businesses by GEMI number.
- 📄 Display key company information including:
  - Name, AFM, Status, Legal Type, and Registration Date.
- 🗺️ Show address
- 👥 List company management details (names, roles, AFMs, dates).
- 🏷️ Display KAD codes and business activity descriptions.
- 📞 Show basic contact information (telephone and email).
- ✅ Input validation with user-friendly alerts.
- 🎨 Built with TailwindCSS for a responsive and modern UI.

---

## 🛠️ How It Works

1. The user inputs a **GEMI number** in the form.
2. Upon submission, the PHP script validates the input (must be numeric).
3. A **POST request** is sent to the `https://publicity.businessportal.gr/api/company/details` API.
4. If the response is successful (`HTTP 200`), the JSON data is parsed.
5. The script dynamically renders company information, management roles, contact info, and activity codes.
6. If no data is found or the API fails, appropriate alerts are shown.

---

## 🧾 Tech Stack

- **Frontend**: HTML, TailwindCSS
- **Backend**: PHP (with cURL)
- **API**: [publicity.businessportal.gr](https://publicity.businessportal.gr)

---

## Contributing

Contributions are welcome! Please open an issue or pull request for any improvements.

## Disclaimer

This script is provided as-is. The author is not responsible for any legal or financial consequences resulting from its use. Always verify critical business information through official channels.
