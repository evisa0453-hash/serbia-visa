# Serbian eID Login Portal Clone

This is a premium, high-fidelity responsive clone of the Serbian electronic ID (eID) login portal (`eid.gov.rs`). 

The project has been modernized with custom typography, clean shadows, smooth transitions, and responsive layout structures, while retaining all core functionalities from the official website.

## Key Features

1. **Pixel-Perfect Responsive Styling**: Designed using modern layouts (CSS Grid/Flexbox) that adapt beautifully to desktop, tablet, and mobile devices.
2. **Three Sign-In Tabs**:
   - **Username & Password**: Input validation (email validation + empty check) and interactive password visibility toggler.
   - **Qualified Electronic Certificate**: Animated smartcard scanning simulation that transitions from reading card to mock success authentication status.
   - **Mobile Application (ConsentID)**: Email input validation matching ConsentID login behaviors.
3. **Language Switcher**: Dynamically changes page copy between **English (en_US)**, **Serbian Cyrillic (sr_RS)**, and **Serbian Latin (lat_RS)** instantly without requiring a page reload.
4. **Accessibility Settings Panel**:
   - **Theme Selector**: Swaps between the **Default** government blue/gold theme, **Black & White** high-contrast theme, and **Inverse** dark-mode.
   - **Font Size Resizer**: Scalable typography helper supporting `Small`, `Normal`, and `Large` text.
5. **Interactive Idle-Timeout Warning**: Detects user inactivity. For demonstration/testing, the idle dialog triggers after **15 seconds** of inactivity. Inside the modal, a countdown ticks down from **30 seconds** (with options to **Dismiss** or **Start over**). If it hits zero, it triggers a mock session timeout screen.
6. **Animated Cookie Banner**: Dynamic slide-up warning cookie consent card that stores dismissal state in the browser's `localStorage` so it does not reappear on reload.

---

## File Contents

- `index.html`: Semantic markup containing the header menus, accessibility panel, main login tabs cards, cookie banner, and timeout modal dialog.
- `styles.css`: Modulized CSS themes and layout properties, animations, and micro-interactions.
- `app.js`: Localization dictionaries, tab controllers, theme/font-size triggers, idle monitors, cookie states, and validation engines.

---

## How to Run

Since the project is built in vanilla HTML, CSS, and JS, you can run it easily:

### Option A: Simple Launch
Simply open your file manager and double-click `index.html` to run it in your default web browser.

### Option B: Local Development Server (Recommended)
Running it through a local HTTP server ensures cookie banner logic, dialog elements, and redirects function without local file permission warnings.

Run one of the following commands in this directory:

**Using Python:**
```bash
python -m http.server 8000
```
Then, open your browser and navigate to `http://localhost:8000`.

**Using Node.js (npx):**
```bash
npx -y browser-sync start --server --files "*.html, *.css, *.js"
```
Or:
```bash
npx -y live-server
```
This will automatically launch the browser and reload the page as you edit code.
