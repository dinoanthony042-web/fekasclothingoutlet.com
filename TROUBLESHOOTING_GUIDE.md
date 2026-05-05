# 🔧 Mobile Navbar - Troubleshooting & Maintenance Guide

## 🚨 Common Issues & Solutions

### Issue 1: Menu Animation is Choppy/Janky

**Symptoms:**
- Menu doesn't slide smoothly
- Frame rate drops during animation
- Animation feels stuttered

**Root Causes:**
1. GPU acceleration not enabled
2. Too many layout shifts
3. Heavy JavaScript during animation
4. Browser rendering issues

**Solutions:**

```css
/* Ensure GPU acceleration is enabled */
.nav-level {
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
}
```

**Debugging Steps:**
1. Open Chrome DevTools (F12)
2. Go to Performance tab
3. Record menu animation
4. Look for red "Long Task" markers
5. Check for forced reflows

**If Problem Persists:**
- Clear browser cache (Ctrl+Shift+Delete)
- Test on actual device (not emulator)
- Check CPU usage during animation
- Try on different browser
- Profile with Lighthouse

---

### Issue 2: Back Button Doesn't Work

**Symptoms:**
- Back button appears but doesn't navigate
- Still on same level after clicking back
- Goes to wrong level

**Root Causes:**
1. `navigationHistory` array not populated
2. Level IDs don't match
3. Event listener not attached
4. JavaScript errors in console

**Solutions:**

```javascript
// Verify navigationHistory is working
function debugNavigation() {
    console.log('Current history:', navigationHistory);
    console.log('Current category:', currentCategory);
    console.log('Current level:', document.querySelector('.nav-level.translate-x-0').id);
}

// Call from browser console to debug
```

**Step-by-Step Fix:**
1. Open browser console (F12)
2. Type: `navigationHistory` (should show array)
3. Type: `goBack()` manually to test
4. Check for JavaScript errors
5. Verify level IDs in HTML

**Check Level IDs:**
```bash
# Should output level IDs
grep 'id="nav-level' app.blade.php
```

---

### Issue 3: Search Bar Not Working

**Symptoms:**
- Search input doesn't respond to typing
- Search bar focuses but nothing happens
- Search results don't appear

**Root Causes:**
1. Input element not found
2. Event listeners not attached
3. Search API not implemented
4. CSS hiding the element

**Solutions:**

```javascript
// Verify search element exists
const searchInput = document.getElementById('mobile-menu-search');
if (searchInput) {
    console.log('Search element found');
} else {
    console.error('Search element NOT found');
}

// Test search event
searchInput.addEventListener('input', function() {
    console.log('Search query:', this.value);
});
```

**Debugging:**
1. Check element exists in HTML
2. Verify ID: `mobile-menu-search`
3. Test in browser console: `document.getElementById('mobile-menu-search')`
4. Check for CSS `display: none`
5. Verify event listeners attached

**Quick Test:**
```javascript
// In browser console:
document.getElementById('mobile-menu-search').focus();
document.getElementById('mobile-menu-search').value = 'test';
```

---

### Issue 4: Colors Not Matching Brand

**Symptoms:**
- Navbar colors different from design
- Buttons showing wrong colors
- Inconsistent color scheme

**Root Causes:**
1. Hex codes entered incorrectly
2. Tailwind classes not updated
3. CSS cached in browser
4. Color values in multiple places

**Solutions:**

**Find All Color References:**
```bash
# Search for all color references
grep -n '#5b1e7e' app.blade.php
grep -n '#5b1e7e' app.css
```

**Update Color Scheme:**
1. Note your new brand colors
2. Search and replace in app.blade.php
3. Update app.css color definitions
4. Clear browser cache (Ctrl+Shift+Delete)
5. Hard refresh page (Ctrl+F5)

**Example Find & Replace:**
```
Find:    #5b1e7e
Replace: #YOUR_PURPLE_COLOR

Find:    #e91e8c
Replace: #YOUR_PINK_COLOR
```

**Verify Changes:**
```javascript
// In browser console, check computed styles:
window.getComputedStyle(document.querySelector('.nav-item-tap')).backgroundColor
```

---

### Issue 5: Menu Stays Open

**Symptoms:**
- Menu won't close after clicking link
- Close button doesn't work
- Menu persists after navigation

**Root Causes:**
1. `closeMobileMenu()` not called
2. Hidden class not applied
3. CSS hiding not working
4. Event listeners not attached

**Solutions:**

```javascript
// Manually close menu
function forceCloseMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    overlay.classList.add('hidden');
    overlay.style.display = 'none'; // Force if CSS fails
}

// Test in console: forceCloseMobileMenu()
```

**Check HTML:**
```html
<!-- Verify this exists -->
<div id="mobile-menu-overlay" class="fixed inset-0 z-50 hidden bg-white md:hidden">
```

**Check CSS:**
```css
/* Verify hidden state works */
.hidden {
    display: none !important;
}
```

**Debug:**
1. Click close button
2. Check if `mobile-menu-overlay` has `hidden` class
3. Open DevTools, inspect element
4. Look for CSS conflicts

---

### Issue 6: Items Don't Animate on Entry

**Symptoms:**
- Menu items appear instantly
- No staggered animation
- Animation delays don't work

**Root Causes:**
1. Animation CSS not loaded
2. Animation delays not applied
3. Nav-level timing wrong
4. CSS animation disabled

**Solutions:**

```css
/* Ensure animations are defined */
@keyframes slideInItem {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Verify animation is applied */
.nav-level.translate-x-0 > div > a:nth-child(1) {
    animation: slideInItem 0.3s ease-out 0.05s both;
}
```

**Debug Steps:**
1. Open DevTools
2. Select a menu item
3. Check "Animations" tab in DevTools
4. Should see animation playing
5. Check animation duration
6. Verify `animation-delay` values

**Force Animation Test:**
```javascript
// Manually trigger animation:
const item = document.querySelector('.nav-level.translate-x-0 > div > a:nth-child(1)');
item.style.animation = 'slideInItem 0.3s ease-out';
```

---

### Issue 7: Tap Feedback Not Visible

**Symptoms:**
- No visual feedback on tap
- Button doesn't scale down
- Ripple effect not showing

**Root Causes:**
1. `:active` styles not applied
2. Touch-action CSS preventing tap
3. Scale animation conflicts
4. z-index issues

**Solutions:**

```css
/* Verify active state exists */
.nav-item-tap:active {
    transform: scale(0.98) !important;
    opacity: 0.8 !important;
}

/* Add ripple effect */
.nav-item-tap::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(91, 30, 126, 0.1);
    transform: translate(-50%, -50%);
}
```

**Debug:**
1. Test on real mobile device (simulators may not show tap)
2. Add debugging border: `border: 1px solid red` on `:active`
3. Check if `pointer-events` is disabled
4. Verify position: relative on parent
5. Test with `-webkit-` prefixes

**Force Test in Console:**
```javascript
// Simulate tap feedback:
const item = document.querySelector('.nav-item-tap');
item.classList.add('active');
setTimeout(() => item.classList.remove('active'), 200);
```

---

### Issue 8: Mobile Menu Shows on Desktop

**Symptoms:**
- Menu visible on desktop/tablet
- Hamburger button always visible
- Should be hidden on MD screens

**Root Causes:**
1. `md:hidden` not working
2. Tailwind CSS not configured
3. Breakpoint values wrong
4. CSS specificity issues

**Solutions:**

```html
<!-- Verify responsive class -->
<button id="mobile-menu-toggle" class="md:hidden p-2">
    <!-- Should be hidden on medium+ screens -->
</button>
```

**Check Tailwind Config:**
```javascript
// In vite.config.js, verify breakpoints:
screens: {
    'sm': '640px',
    'md': '768px',  // Menu should hide here
    'lg': '1024px',
}
```

**Force Hide on Desktop:**
```css
@media (min-width: 768px) {
    #mobile-menu-toggle {
        display: none !important;
    }
    #mobile-menu-overlay {
        display: none !important;
    }
}
```

**Test Breakpoints:**
1. Open DevTools (F12)
2. Click "Toggle device toolbar" (Ctrl+Shift+M)
3. Drag to MD breakpoint (768px)
4. Menu should hide
5. Hamburger should hide

---

### Issue 9: Links Navigate But Menu Doesn't Close

**Symptoms:**
- Clicking link navigates to page
- Menu stays open behind page
- Need to manually close

**Root Causes:**
1. Auto-close timeout too long
2. Page doesn't fully reload
3. Router not refreshing
4. Close function not triggered

**Solutions:**

```javascript
// Ensure auto-close on link click
document.querySelectorAll('#mobile-menu-overlay a').forEach(link => {
    if (!link.getAttribute('onclick')) {
        link.addEventListener('click', function() {
            setTimeout(closeMobileMenu, 100);
        });
    }
});

// Increase timeout if needed:
setTimeout(closeMobileMenu, 300); // Changed from 100ms
```

**Debug:**
1. Add console log to close function
2. Check if function is being called
3. Verify timeout is executing
4. Check if page is actually reloading

---

## 🧹 Maintenance Tasks

### Weekly Checklist
- [ ] Monitor analytics for menu usage
- [ ] Check for JavaScript errors in console
- [ ] Test on latest browsers
- [ ] Verify search functionality
- [ ] Check performance metrics

### Monthly Checklist
- [ ] Review user feedback
- [ ] Test on various devices
- [ ] Check animation smoothness
- [ ] Verify all links work
- [ ] Monitor page load times

### Quarterly Checklist
- [ ] Update dependencies
- [ ] Audit accessibility
- [ ] Review color contrast
- [ ] Test with screen readers
- [ ] Performance optimization
- [ ] Update documentation

---

## 📊 Performance Monitoring

### Key Metrics to Track

```javascript
// Monitor menu performance
const metrics = {
    menuOpenTime: 0,
    animationFrameRate: 60,
    jankEvents: 0,
    errorCount: 0
};

// Track menu interactions
analytics.track('Mobile Menu Opened', {
    timestamp: Date.now(),
    userAgent: navigator.userAgent
});
```

### Browser DevTools Tips

**Check Performance:**
1. F12 → Performance tab
2. Click record
3. Open menu
4. Stop recording
5. Look for dropped frames (red bars)

**Check Animations:**
1. F12 → Animations tab
2. Open menu
3. Should see animation timeline
4. Verify smooth progression

**Check Memory:**
1. F12 → Memory tab
2. Take heap snapshot
3. Open/close menu 10 times
4. Take another snapshot
5. Compare for leaks

---

## 🐛 Advanced Debugging

### Enable Detailed Logging

```javascript
// Add to app.blade.php
const DEBUG = true;

function log(...args) {
    if (DEBUG) console.log(...args);
}

// Usage:
log('Navigation to level:', levelId);
log('Current history:', navigationHistory);
log('Current category:', currentCategory);
```

### Trace Function Calls

```javascript
// Wrap key functions with logging
const originalNavigate = navigateToLevel;
navigateToLevel = function(levelId) {
    console.trace('navigateToLevel called:', levelId);
    return originalNavigate.call(this, levelId);
};
```

### Monitor DOM Changes

```javascript
// Watch for menu mutations
const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        console.log('DOM changed:', mutation);
    });
});

observer.observe(document.getElementById('mobile-menu-overlay'), {
    attributes: true,
    childList: true,
    subtree: true
});
```

---

## 🔄 Recovery Procedures

### If Menu Breaks Completely

**Step 1: Restore from Backup**
```bash
git checkout HEAD -- resources/views/layouts/app.blade.php
```

**Step 2: Clear Cache**
```bash
php artisan cache:clear
php artisan view:clear
npm run dev
```

**Step 3: Verify File**
```bash
grep "id=\"mobile-menu-toggle\"" resources/views/layouts/app.blade.php
```

### If Styles Not Loading

**Step 1: Rebuild CSS**
```bash
npm run dev
```

**Step 2: Clear Browser Cache**
- Ctrl + Shift + Delete
- Select "All time"
- Clear caches

**Step 3: Hard Refresh**
- Ctrl + Shift + R (Windows)
- Cmd + Shift + R (Mac)

### If JavaScript Errors

**Step 1: Check Console**
```javascript
// In browser console:
console.log(typeof navigateToLevel);  // Should be 'function'
console.log(navigationHistory);        // Should be array
```

**Step 2: Reload Dependencies**
```bash
npm install
npm run dev
```

**Step 3: Restart Server**
```bash
# Stop current server
# Restart with:
npm run dev
```

---

## 📱 Device-Specific Issues

### iOS Safari
**Issue:** Slow animation
**Solution:** Test on real device, enable 60fps mode
```css
/* iOS optimization */
-webkit-transform: translate3d(0, 0, 0);
-webkit-backface-visibility: hidden;
```

### Android Chrome
**Issue:** Jank on low-end devices
**Solution:** Reduce animation complexity, use simpler easing
```css
/* Android optimization */
animation: slideInItem 0.2s linear; /* Simpler easing */
```

### Firefox Mobile
**Issue:** Scrollbar visible
**Solution:** Use scrollbar-hide class properly
```css
scrollbar-width: none;  /* Firefox 64+ */
```

---

## 🔐 Security Considerations

### Regular Security Audit
- [ ] Check CSRF tokens on forms
- [ ] Verify input validation
- [ ] Audit authentication flows
- [ ] Check for XSS vulnerabilities
- [ ] Review access controls

### Safe Customization
- Don't store sensitive data in menu
- Sanitize all user inputs
- Use Laravel's built-in security
- Keep dependencies updated
- Monitor security advisories

---

## 📞 Getting Help

### Documentation
- See [NAVBAR_DOCUMENTATION.md](./NAVBAR_DOCUMENTATION.md) for features
- See [NAVBAR_QUICK_REFERENCE.md](./NAVBAR_QUICK_REFERENCE.md) for quick fixes
- See [VISUAL_GUIDE.md](./VISUAL_GUIDE.md) for visual reference

### Debug Resources
- Browser console: F12
- DevTools Performance: F12 → Performance
- Network tab: F12 → Network
- Console logs: window logs

### Common Fix: Hard Refresh
**Windows/Linux:** Ctrl + Shift + R  
**Mac:** Cmd + Shift + R

---

## 📝 Issue Reporting Template

```markdown
## Bug Report

**Issue Description:**
[Describe the problem]

**Steps to Reproduce:**
1. Step one
2. Step two
3. Step three

**Expected Behavior:**
[What should happen]

**Actual Behavior:**
[What actually happened]

**Device Info:**
- Device: [iPhone 12 / Android 11 / etc]
- Browser: [Chrome / Safari / etc]
- OS: [iOS 14 / Android 11 / etc]

**Console Errors:**
[Paste any errors from console]

**Screenshots:**
[Attach if possible]
```

---

**Last Updated:** May 5, 2026  
**Status:** ✅ Production Ready

*For more help, consult the comprehensive documentation or contact support.*
