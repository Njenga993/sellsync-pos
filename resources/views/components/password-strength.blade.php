<div x-data="{
    password: '',
    get lengthOk() { return this.password.length >= 8; },
    get uppercaseOk() { return /[A-Z]/.test(this.password); },
    get lowercaseOk() { return /[a-z]/.test(this.password); },
    get numberOk() { return /[0-9]/.test(this.password); },
    get specialOk() { return /[\W_]/.test(this.password); },
    get strength() {
        let score = 0;
        if (this.lengthOk) score++;
        if (this.uppercaseOk) score++;
        if (this.lowercaseOk) score++;
        if (this.numberOk) score++;
        if (this.specialOk) score++;
        return score;
    },
    get strengthLabel() {
        if (this.strength <= 1) return 'Weak';
        if (this.strength <= 3) return 'Fair';
        if (this.strength <= 4) return 'Good';
        return 'Strong';
    },
    get strengthColor() {
        if (this.strength <= 1) return '#dc2626';
        if (this.strength <= 3) return '#d97706';
        if (this.strength <= 4) return '#1a56db';
        return '#16a34a';
    }
}" style="margin-top:8px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
        <div style="flex:1;height:4px;background:#e4e7ef;border-radius:99px;overflow:hidden">
            <div style="height:100%;width:0%;border-radius:99px;transition:all 0.3s"
                 :style="'width:' + (strength / 5 * 100) + '%;background:' + strengthColor"></div>
        </div>
        <span style="font-size:11px;font-weight:600;font-family:'Outfit',sans-serif"
              :style="'color:' + strengthColor" x-text="strengthLabel"></span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;font-size:11px;font-family:'Outfit',sans-serif">
        <div style="display:flex;align-items:center;gap:6px;color:#9ca3af" :style="lengthOk ? 'color:#16a34a' : ''">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span>8+ characters</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px;color:#9ca3af" :style="uppercaseOk ? 'color:#16a34a' : ''">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Uppercase letter</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px;color:#9ca3af" :style="lowercaseOk ? 'color:#16a34a' : ''">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Lowercase letter</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px;color:#9ca3af" :style="numberOk ? 'color:#16a34a' : ''">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Number</span>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:6px;margin-top:4px;font-size:11px;font-family:'Outfit',sans-serif;color:#9ca3af"
         :style="specialOk ? 'color:#16a34a' : ''">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Special character (!@#$%^&*)</span>
    </div>
</div>