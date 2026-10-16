<?php
/**
 * RogiSewa Banner Generator
 * Generates 15 professional marketing banners as SVG files
 * Run: php generate_banners.php
 */

$outputDir = __DIR__ . '/public/img/banners/';

// ─── Helper: SVG banner template ───────────────────────────────────────────
function makeBanner(
    string $file,
    string $num,
    string $bg1, string $bg2, string $bg3,      // gradient stops
    string $accentColor,                          // accent / highlight color
    string $iconEmoji,                            // feature icon emoji
    string $iconBg1, string $iconBg2,            // icon gradient
    string $titleLine1, string $titleLine2,       // big title
    string $hindiLine1, string $hindiLine2,       // hindi subtitle
    array  $bottomFeatures,                       // [emoji, label, hindi] x4
    string $phoneTitle,                           // phone screen header
    array  $phoneItems,                           // screen list items [emoji, title, desc]
    array  $rightCards,                           // right panel [emoji, title, hindi]
    string $rightHeading,                         // right panel heading
    string $sloganLine1, string $sloganLine2,     // bottom slogan
    string $ctaText,                              // CTA button text
    string $footerNote                            // footer note
) : string {
    $bottomFeatHTML = '';
    $xArr = [28, 112, 196, 280];
    $divArr = [104, 188, 272];
    foreach ($bottomFeatures as $i => $f) {
        $x = $xArr[$i];
        $cx = $x + 34;
        $bottomFeatHTML .= <<<SVG
  <rect x="{$x}" y="308" width="68" height="68" rx="14" fill="white" fill-opacity="0.1"/>
  <text x="{$cx}" y="343" text-anchor="middle" font-size="26" font-family="Arial">{$f[0]}</text>
  <text x="{$cx}" y="360" text-anchor="middle" font-size="9" font-weight="bold" fill="white" font-family="Arial">{$f[1]}</text>
  <text x="{$cx}" y="375" text-anchor="middle" font-size="8" fill="white" fill-opacity="0.6" font-family="Arial">{$f[2]}</text>
SVG;
        if ($i < 3) {
            $dx = $divArr[$i];
            $bottomFeatHTML .= "\n  <line x1=\"{$dx}\" y1=\"312\" x2=\"{$dx}\" y2=\"400\" stroke=\"white\" stroke-opacity=\"0.2\" stroke-width=\"1\"/>\n";
        }
    }

    $phoneItemsHTML = '';
    $phoneColors = ['#e3f2fd','#fce4ec','#e8f5e9','#fff8e1','#f3e5f5'];
    $yPos = 158;
    foreach ($phoneItems as $i => $item) {
        $bg    = $phoneColors[$i % count($phoneColors)];
        $yT1   = $yPos + 12;
        $yT2   = $yPos + 11;
        $yT3   = $yPos + 23;
        $phoneItemsHTML .= "  <rect x=\"374\" y=\"{$yPos}\" width=\"172\" height=\"32\" rx=\"8\" fill=\"{$bg}\" stroke=\"#eeeeee\" stroke-width=\"1\"/>\n";
        $phoneItemsHTML .= "  <text x=\"382\" y=\"{$yT1}\" font-size=\"14\" font-family=\"Arial\">{$item[0]}</text>\n";
        $phoneItemsHTML .= "  <text x=\"400\" y=\"{$yT2}\" font-size=\"9\" font-weight=\"bold\" fill=\"#1565c0\" font-family=\"Arial\">{$item[1]}</text>\n";
        $phoneItemsHTML .= "  <text x=\"400\" y=\"{$yT3}\" font-size=\"8\" fill=\"#888\" font-family=\"Arial\">{$item[2]}</text>\n";
        $yPos += 36;
    }

    $rightHTML = '';
    $ryArr = [108, 200, 292, 384, 456];
    foreach ($rightCards as $i => $card) {
        $ry         = $ryArr[$i];
        $ryIcon     = $ry + 8;
        $ryT1       = $ry - 6;
        $ryT2       = $ry + 9;
        $isLast     = $i === count($rightCards) - 1;
        $cirFill    = $isLast ? $accentColor : 'white';
        $cirOpacity = $isLast ? '0.2' : '0.1';
        $borderColor   = $isLast ? $accentColor : 'white';
        $borderOpacity = $isLast ? '0.5' : '0.25';
        $titleColor    = $isLast ? $accentColor : 'white';
        $rightHTML .= "  <circle cx=\"820\" cy=\"{$ry}\" r=\"32\" fill=\"{$cirFill}\" fill-opacity=\"{$cirOpacity}\" stroke=\"{$borderColor}\" stroke-opacity=\"{$borderOpacity}\" stroke-width=\"1.5\"/>\n";
        $rightHTML .= "  <text x=\"820\" y=\"{$ryIcon}\" text-anchor=\"middle\" font-size=\"28\" font-family=\"Arial\">{$card[0]}</text>\n";
        $rightHTML .= "  <text x=\"862\" y=\"{$ryT1}\" font-size=\"13\" font-weight=\"bold\" fill=\"{$titleColor}\" font-family=\"Arial\">{$card[1]}</text>\n";
        $rightHTML .= "  <text x=\"862\" y=\"{$ryT2}\" font-size=\"10\" fill=\"white\" fill-opacity=\"0.65\" font-family=\"Arial\">{$card[2]}</text>\n";
        if ($i < count($rightCards) - 1) {
            $lineY1 = $ry + 32;
            $lineY2 = $ryArr[$i+1] - 32;
            $rightHTML .= "  <line x1=\"820\" y1=\"{$lineY1}\" x2=\"820\" y2=\"{$lineY2}\" stroke=\"white\" stroke-opacity=\"0.2\" stroke-width=\"2\" stroke-dasharray=\"5,3\"/>\n";
        }
    }

    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1024" height="512" viewBox="0 0 1024 512">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%"   stop-color="{$bg1}"/>
      <stop offset="55%"  stop-color="{$bg2}"/>
      <stop offset="100%" stop-color="{$bg3}"/>
    </linearGradient>
    <linearGradient id="iconG" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$iconBg1}"/>
      <stop offset="100%" stop-color="{$iconBg2}"/>
    </linearGradient>
    <filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="10" flood-color="#000" flood-opacity="0.3"/></filter>
  </defs>

  <!-- BG -->
  <rect width="1024" height="512" fill="url(#bg)"/>
  <circle cx="900" cy="70"  r="200" fill="white" fill-opacity="0.03"/>
  <circle cx="960" cy="450" r="160" fill="white" fill-opacity="0.04"/>
  <circle cx="70"  cy="420" r="160" fill="white" fill-opacity="0.03"/>
  <text x="810" y="105" font-size="26" fill="white" fill-opacity="0.07" font-family="Arial">+</text>
  <text x="930" y="220" font-size="20" fill="white" fill-opacity="0.06" font-family="Arial">+</text>
  <text x="55"  y="290" font-size="18" fill="white" fill-opacity="0.06" font-family="Arial">+</text>
  <text x="760" y="430" font-size="16" fill="white" fill-opacity="0.06" font-family="Arial">+</text>

  <!-- LOGO -->
  <rect x="28" y="20" width="220" height="52" rx="10" fill="white" fill-opacity="0.1"/>
  <circle cx="54" cy="46" r="18" fill="white" fill-opacity="0.18"/>
  <text x="54" y="53" text-anchor="middle" font-size="20" font-family="Arial">🏥</text>
  <text x="78" y="40" font-size="20" font-weight="bold" fill="white" font-family="Arial">Rogi<tspan fill="{$accentColor}">Sewa</tspan></text>
  <text x="78" y="58" font-size="10" fill="white" fill-opacity="0.7" font-family="Arial">Your Health  •  Our Priority</text>

  <!-- FEATURE ICON -->
  <rect x="28" y="92" width="64" height="64" rx="16" fill="url(#iconG)" filter="url(#sh)"/>
  <text x="60" y="134" text-anchor="middle" font-size="34" font-family="Arial">{$iconEmoji}</text>

  <!-- TITLE -->
  <text x="104" y="128" font-size="44" font-weight="bold" fill="white" font-family="Arial">{$titleLine1}</text>
  <text x="28"  y="178" font-size="44" font-weight="bold" fill="{$accentColor}" font-family="Arial">{$titleLine2}</text>
  <text x="28" y="215" font-size="19" font-weight="bold" fill="white" font-family="Arial">{$hindiLine1}</text>
  <text x="28" y="242" font-size="19" font-weight="bold" fill="white" font-family="Arial">{$hindiLine2}</text>
  <line x1="28" y1="260" x2="330" y2="260" stroke="white" stroke-opacity="0.2" stroke-width="1"/>

  <!-- BOTTOM FEATURE ICONS -->
{$bottomFeatHTML}

  <!-- PHONE MOCKUP -->
  <rect x="358" y="55" width="204" height="398" rx="28" fill="#1a1a2e" filter="url(#sh)"/>
  <rect x="362" y="59" width="196" height="390" rx="25" fill="#0d1117"/>
  <rect x="366" y="70" width="188" height="368" rx="20" fill="#f8f9fa"/>
  <rect x="424" y="62" width="72" height="16" rx="8" fill="#1a1a2e"/>
  <rect x="366" y="70" width="188" height="36" rx="20" fill="{$bg2}"/>
  <rect x="366" y="86" width="188" height="20" fill="{$bg2}"/>
  <text x="460" y="93" text-anchor="middle" font-size="11" font-weight="bold" fill="white" font-family="Arial">RogiSewa</text>
  <text x="378" y="122" font-size="12" font-weight="bold" fill="{$bg1}" font-family="Arial">{$phoneTitle}</text>
{$phoneItemsHTML}

  <!-- RIGHT PANEL -->
  <text x="720" y="50" font-size="17" font-weight="bold" fill="white" font-family="Arial">{$rightHeading}</text>
  <line x1="720" y1="58" x2="1000" y2="58" stroke="white" stroke-opacity="0.18" stroke-width="1"/>
{$rightHTML}

  <!-- SLOGAN -->
  <text x="690" y="472" text-anchor="middle" font-size="13" fill="white" fill-opacity="0.75" font-family="Arial" font-style="italic">{$sloganLine1}</text>
  <text x="690" y="490" text-anchor="middle" font-size="13" font-weight="bold" fill="{$accentColor}" font-family="Arial">{$sloganLine2}</text>

  <!-- BOTTOM BAR -->
  <rect x="0" y="460" width="1024" height="52" fill="black" fill-opacity="0.28"/>
  <rect x="28" y="471" width="210" height="32" rx="16" fill="{$accentColor}"/>
  <text x="133" y="491" text-anchor="middle" font-size="12" font-weight="bold" fill="{$bg1}" font-family="Arial">{$ctaText}</text>
  <text x="252" y="484" font-size="11" fill="white" fill-opacity="0.5" font-family="Arial">|</text>
  <text x="266" y="484" font-size="11" fill="white" fill-opacity="0.7" font-family="Arial">📱 Available on Google Play Store</text>
  <text x="790" y="484" font-size="11" fill="white" fill-opacity="0.45" font-family="Arial">{$footerNote}</text>
</svg>
SVG;
}

// ─── Define all 13 remaining banners ───────────────────────────────────────

$banners = [

  // 3. BrainFit
  '03-brainfit' => makeBanner(
    '03-brainfit', '3',
    '#4a0080','#6a1b9a','#8e24aa',
    '#ce93d8',
    '🧠', '#7b1fa2','#4a0080',
    'BrainFit', '',
    'दिमाग तेज़ करें,', 'बेहतर सोचें',
    [['🎯','Memory','याददाश्त बढ़ाएं'],['💡','Focus','एकाग्रता बढ़ाएं'],['🧘','Mental Fitness','मानसिक स्वास्थ्य'],['📈','Progress','दैनिक प्रगति']],
    'BrainFit',
    [['🎯','Memory Games','Remember patterns'],['🧩','Logic Puzzles','Sharpen thinking'],['⏱️','Speed Tests','Reaction time'],['💤','Mindfulness','Stress relief']],
    [['🎯','Memory','याददाश्त'],['💡','Focus','एकाग्रता'],['🧩','Logic','तर्कशक्ति'],['📊','Track Progress','दैनिक ट्रैकिंग'],['🏆','Achieve Goals','लक्ष्य हासिल करें']],
    'Brain Training Levels',
    '♥ तेज़ दिमाग,','बेहतर जीवन',
    '⬇ Download Patient App',
    'rogisewa.com  •  3/15'
  ),

  // 4. Calorie Burn
  '04-calorie-burn' => makeBanner(
    '04-calorie-burn', '4',
    '#b71c1c','#d32f2f','#e53935',
    '#ffab91',
    '🔥', '#c62828','#b71c1c',
    'Calorie', 'Burn',
    'दैनिक प्रगति में', 'आपका साथी',
    [['📊','Track Calories','कैलोरी ट्रैक'],['🥗','Healthy Diet','सही खानपान'],['⚖️','Weight Goal','वज़न लक्ष्य'],['💪','Stay Fit','फिट रहें']],
    'Calorie Tracker',
    [['🔥','Today: 420 kcal','Calories burned'],['🏃','Steps: 6,200','Walk more!'],['🥗','Lunch: 380 kcal','Calories eaten'],['💧','Water: 4/8 glasses','Stay hydrated']],
    [['🏃','Light Exercise','हल्की कसरत'],['🚶','Moderate Walk','तेज़ चलना'],['🏊','Intense Workout','जोरदार व्यायाम'],['🏋️','Strength Train','शक्ति प्रशिक्षण'],['🏆','Goal Achieved!','लक्ष्य पूरा']],
    'Activity Levels',
    '♥ फिट रहें,','स्वस्थ रहें',
    '⬇ Download Patient App',
    'rogisewa.com  •  4/15'
  ),

  // 5. Vaccine Tracker
  '05-vaccine-tracker' => makeBanner(
    '05-vaccine-tracker', '5',
    '#0a3d8f','#1565c0','#1976d2',
    '#69f0ae',
    '💉', '#1976d2','#0a3d8f',
    'Vaccine', 'Tracker',
    'सभी टीकाकरण रिकॉर्ड', 'एक जगह',
    [['📅','Schedule','टीका कार्यक्रम'],['🔔','Reminders','याद दिलाएं'],['📋','Records','पूरा रिकॉर्ड'],['✅','Completed','पूर्ण टीके']],
    'Vaccine Schedule',
    [['💉','BCG — Done ✓','Birth vaccine'],['💉','OPV Dose 1 — Done ✓','6 weeks'],['💉','Hepatitis B — Due ⏰','10 weeks'],['💉','MMR — Pending','9 months']],
    [['👶','0–2 Weeks','जन्म टीके'],['🍼','6 Weeks','प्रारंभिक टीके'],['🧒','3–6 Months','आवश्यक टीके'],['👦','9–12 Months','बूस्टर टीके'],['🎓','5 Years+','स्कूल टीके']],
    'Vaccination Timeline',
    '♥ सुरक्षित बच्चा,','स्वस्थ परिवार',
    '⬇ Download Patient App',
    'rogisewa.com  •  5/15'
  ),

  // 6. Period Tracker
  '06-period-tracker' => makeBanner(
    '06-period-tracker', '6',
    '#880e4f','#c2185b','#e91e63',
    '#f48fb1',
    '🌸', '#e91e63','#880e4f',
    'Period', 'Tracker',
    'मासिक धर्म', 'पर नज़र रखें',
    [['🔄','Cycle Tracking','साइकल ट्रैक'],['📆','Predictions','भविष्यवाणी'],['📝','Log Symptoms','लक्षण दर्ज करें'],['🔔','Reminders','याद दिलाएं']],
    'Period Tracker',
    [['🌸','Next Period: 5 days','Predicted date'],['📊','Cycle: 28 days','Regular cycle'],['💊','Iron: Take today','Health reminder'],['📝','Log today\'s mood','How are you?']],
    [['🌱','Follicular Phase','फॉलिकुलर चरण'],['🌸','Ovulation','ओव्यूलेशन'],['🍂','Luteal Phase','ल्यूटियल चरण'],['❤️','Menstruation','मासिक धर्म'],['🔄','Next Cycle','अगला साइकल']],
    'Cycle Phases',
    '♥ अपनी सेहत का,','खुद रखें ख्याल',
    '⬇ Download Patient App',
    'rogisewa.com  •  6/15'
  ),

  // 7. Baby Growth
  '07-baby-growth' => makeBanner(
    '07-baby-growth', '7',
    '#0a3d8f','#1565c0','#42a5f5',
    '#69f0ae',
    '👶', '#1976d2','#0d47a1',
    'Baby', 'Growth',
    'हर कदम पर', 'सही विकास',
    [['📈','Growth Chart','विकास चार्ट'],['🎯','Milestones','माइलस्टोन'],['🥛','Nutrition','पोषण गाइड'],['💪','Development','शारीरिक विकास']],
    'Baby Growth Tracker',
    [['👶','Weight: 8.5 kg','7–9 kg is normal'],['📏','Height: 70 cm','Within range'],['🎯','Sitting — Achieved!','6 month milestone'],['🍼','Nutrition: Solid food','Start at 6 months']],
    [['🤱','0–3 Months','नवजात चरण'],['🍼','3–6 Months','बैठना सीखना'],['🧸','6–9 Months','घुटने चलना'],['🚶','9–12 Months','खड़े होना'],['🎓','12+ Months','पहला कदम']],
    'Baby Milestones',
    '♥ स्वस्थ शिशु,','खुशहाल परिवार',
    '⬇ Download Patient App',
    'rogisewa.com  •  7/15'
  ),

  // 8. Medicine Info
  '08-medicine-info' => makeBanner(
    '08-medicine-info', '8',
    '#004d40','#00695c','#00897b',
    '#80cbc4',
    '💊', '#00796b','#004d40',
    'Medicine Info', '/ Reminder',
    'सही दवा,', 'सही समय',
    [['💊','Drug Info','दवा जानकारी'],['🔔','Reminders','याद दिलाएं'],['📏','Dosage','खुराक'],['⚠️','Side Effects','दुष्प्रभाव']],
    'My Medicines',
    [['💊','Paracetamol 500mg','1 tab • 3 times daily'],['💊','Amoxicillin 250mg','8 AM and 8 PM'],['🧴','Vitamin D3','1 tab every morning'],['💉','B12 Injection','Once a month']],
    [['📖','Know Your Medicine','दवा पहचानें'],['⏰','Set Reminder','रिमाइंडर सेट'],['📏','Check Dosage','खुराक जांचें'],['🔄','Refill Alert','दवा खत्म होने पर'],['✅','Mark as Taken','ले ली दवा']],
    'Medicine Routine',
    '♥ सही दवा,','सही समय पर',
    '⬇ Download Patient App',
    'rogisewa.com  •  8/15'
  ),

  // 9. Food AI
  '09-food-ai' => makeBanner(
    '09-food-ai', '9',
    '#33691e','#558b2f','#689f38',
    '#ccff90',
    '🥗', '#558b2f','#33691e',
    'Food AI', '',
    'AI से पाएं', 'बेहतर डाइट प्लान',
    [['🍽️','Meal Plan','भोजन योजना'],['🧪','Nutrition Facts','पोषण तथ्य'],['🥦','Healthy Recipes','स्वस्थ व्यंजन'],['💪','Fitness Diet','फिटनेस आहार']],
    'AI Diet Plan',
    [['🥗','Breakfast: Oats + Banana','320 kcal • High fiber'],['🍱','Lunch: Dal + Rice + Sabzi','580 kcal • Balanced'],['🍎','Snack: Apple + Nuts','210 kcal • Protein'],['🍲','Dinner: Khichdi + Curd','420 kcal • Light']],
    [['🤖','AI Analysis','AI विश्लेषण'],['🎯','Personal Goals','व्यक्तिगत लक्ष्य'],['📊','Nutrient Track','पोषक ट्रैक'],['🛒','Grocery List','खरीदारी सूची'],['✅','Daily Target','दैनिक लक्ष्य']],
    'Your Daily Diet Plan',
    '♥ सही खाना,','स्वस्थ जीवन',
    '⬇ Download Patient App',
    'rogisewa.com  •  9/15'
  ),

  // 10. Sehat AI
  '10-sehat-ai' => makeBanner(
    '10-sehat-ai', '10',
    '#212121','#37474f','#546e7a',
    '#69f0ae',
    '🤖', '#455a64','#263238',
    'Sehat AI', '',
    'AI आपका', 'स्वास्थ्य सहायक',
    [['💬','Ask Health','सवाल पूछें'],['🤖','AI Guidance','AI सलाह'],['❤️','Stay Healthy','स्वस्थ रहें'],['🕐','24/7 Available','हमेशा उपलब्ध']],
    'Sehat AI Chat',
    [['👤','Mujhe sir dard hai...','User question'],['🤖','Stress ya dehydration...','AI reply'],['👤','Kya khana chahiye?','Follow-up'],['🤖','Light food lein, paani...','AI suggests']],
    [['💬','Ask Symptoms','लक्षण पूछें'],['💊','Medicine Info','दवा जानकारी'],['🥗','Diet Advice','आहार सलाह'],['🏃','Exercise Tips','व्यायाम टिप्स'],['🚨','Emergency Guide','आपात जानकारी']],
    'AI Health Topics',
    '♥ AI का साथ,','सेहत के साथ',
    '⬇ Try Sehat AI Now',
    'rogisewa.com  •  10/15'
  ),

  // 11. Water Reminder
  '11-water-reminder' => makeBanner(
    '11-water-reminder', '11',
    '#01579b','#0277bd','#0288d1',
    '#80d8ff',
    '💧', '#0288d1','#01579b',
    'Water', 'Reminder',
    'हर दिन पर्याप्त', 'पानी पिएं',
    [['💧','Stay Hydrated','हाइड्रेटेड रहें'],['⚡','Better Energy','बेहतर ऊर्जा'],['❤️','Good Health','अच्छी सेहत'],['🔔','Daily Alerts','दैनिक अलर्ट']],
    'Water Tracker',
    [['💧','Today: 4/8 glasses','Drink more water!'],['⏰','Reminder: 2:00 PM','Next water alarm'],['📊','Weekly: 85% target','Good progress'],['💡','Tip: Drink before meals','Health tip']],
    [['☀️','Morning','सुबह 2 गिलास'],['🌤️','Mid-Morning','दोपहर से पहले'],['🌞','Afternoon','दोपहर 2 गिलास'],['🌆','Evening','शाम 2 गिलास'],['🌙','Night','रात 1 गिलास']],
    'Daily Water Schedule',
    '♥ पानी पिएं,','स्वस्थ रहें',
    '⬇ Download Patient App',
    'rogisewa.com  •  11/15'
  ),

  // 12. Eye Test
  '12-eye-test' => makeBanner(
    '12-eye-test', '12',
    '#0a3d8f','#1565c0','#1e88e5',
    '#90caf9',
    '👁️', '#1976d2','#0d47a1',
    'Eye Test', '',
    'साफ नज़र,', 'बेहतर जिंदगी',
    [['👁️','Vision Check','दृष्टि जांच'],['📋','Eye Report','आँखों की रिपोर्ट'],['🛡️','Eye Care Tips','देखभाल के टिप्स'],['👓','Glasses Guide','चश्मे की जानकारी']],
    'Eye Test',
    [['👁️','Visual Acuity: 6/6','Normal vision'],['🎨','Color Vision: Normal','No deficiency'],['💡','Screen time: 6hrs','Reduce by 2hrs'],['🥕','Eat Vitamin A foods','For eye health']],
    [['E','Row 1: Big Letters','साफ दिखना'],['FP','Row 2: Medium','थोड़ा छोटा'],['TOZ','Row 3: Smaller','ध्यान से देखें'],['LPED','Row 4: Small','ध्यान से'],['PECFD','Row 5: Fine','6/6 दृष्टि']],
    'Snellen Eye Chart',
    '♥ साफ नज़र,','उज्जवल जीवन',
    '⬇ Download Patient App',
    'rogisewa.com  •  12/15'
  ),

  // 13. Find Doctors
  '13-find-doctors' => makeBanner(
    '13-find-doctors', '13',
    '#0a2463','#1565c0','#0d47a1',
    '#69f0ae',
    '👨‍⚕️', '#1976d2','#0a2463',
    'Find', 'Doctors',
    'अपने नज़दीकी', 'डॉक्टर खोजें',
    [['✅','Verified','सत्यापित डॉक्टर'],['👨‍⚕️','Expert Doctors','विशेषज्ञ'],['⭐','Ratings','रेटिंग और समीक्षा'],['📅','Book Now','अभी बुक करें']],
    'Find Doctors',
    [['👨‍⚕️','Dr. Ramesh Kumar','Cardiologist • Patna'],['👩‍⚕️','Dr. Sunita Sharma','Gynecologist • Delhi'],['👨‍⚕️','Dr. Ajay Verma','Pediatrician • Mumbai'],['👩‍⚕️','Dr. Priya Singh','Dermatologist • Pune']],
    [['🫀','Cardiology','हृदय रोग'],['🧠','Neurology','नसों की बीमारी'],['👶','Pediatrics','बाल चिकित्सा'],['🦷','Dentistry','दंत चिकित्सा'],['👁️','Ophthalmology','नेत्र विज्ञान']],
    'Search by Specialization',
    '♥ सही डॉक्टर,','सही इलाज',
    '🔍 Find Doctor Now',
    'rogisewa.com  •  13/15'
  ),

  // 14. Find Hospitals
  '14-find-hospitals' => makeBanner(
    '14-find-hospitals', '14',
    '#004d40','#00695c','#00897b',
    '#80cbc4',
    '🏥', '#00796b','#004d40',
    'Find', 'Hospitals',
    'नज़दीकी अस्पताल', 'तुरंत खोजें',
    [['📍','Location','स्थान खोजें'],['🏥','Facilities','सुविधाएं'],['⭐','Reviews','समीक्षाएं'],['📞','Contact','संपर्क करें']],
    'Find Hospitals',
    [['🏥','City Hospital — 1.2km','Multi-specialty'],['🏥','Apollo Clinic — 2.4km','Cardiology'],['🏥','Govt Medical — 3.1km','24x7 Emergency'],['🏥','Sunrise Hospital — 4km','Gynecology']],
    [['📍','Nearest','सबसे नज़दीक'],['⭐','Top Rated','सर्वश्रेष्ठ'],['🔬','Specialty','विशेषज्ञता'],['🚨','Emergency','आपातकालीन'],['✅','Verified','सत्यापित']],
    'Search Options',
    '♥ सही अस्पताल,','सही समय पर',
    '🏥 Find Hospital Now',
    'rogisewa.com  •  14/15'
  ),

  // 15. Staff Tracking
  '15-staff-tracking' => makeBanner(
    '15-staff-tracking', '15',
    '#0a3d8f','#1565c0','#1976d2',
    '#69f0ae',
    '👥', '#1976d2','#0a3d8f',
    'Staff', 'Tracking',
    'स्टाफ उपस्थिति', 'पर नज़र',
    [['📊','Attendance','उपस्थिति'],['📋','Reports','रिपोर्ट्स'],['⚙️','Management','प्रबंधन'],['💼','Roles & Perms','भूमिकाएं']],
    'Staff Attendance',
    [['👨','Dr. Ramesh — Present ✓','Checked in 9:00 AM'],['👩','Nurse Priya — Present ✓','Checked in 8:45 AM'],['👦','Staff Ajay — Absent ✗','Not checked in'],['👩','Admin Seema — Present ✓','Checked in 9:15 AM']],
    [['👥','All Staff','सभी स्टाफ'],['✅','Present Today','आज उपस्थित'],['❌','Absent','अनुपस्थित'],['📊','Monthly Report','मासिक रिपोर्ट'],['⚙️','Manage Roles','भूमिका प्रबंधन']],
    'Staff Overview',
    '♥ सुव्यवस्थित क्लिनिक,','बेहतर सेवा',
    '⬇ Download Doctor App',
    'rogisewa.com  •  15/15'
  ),

];

// ─── Write all banner files ────────────────────────────────────────────────
foreach ($banners as $name => $svg) {
    $path = $outputDir . 'banner-' . $name . '.svg';
    file_put_contents($path, $svg);
    echo "✓ Created: banner-{$name}.svg\n";
}

echo "\n✅ All 13 remaining banners generated successfully!\n";
echo "📁 Location: {$outputDir}\n";
