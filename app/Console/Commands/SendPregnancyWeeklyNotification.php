<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PregnancyTracking;
use App\Services\FirebaseNotification;
use App\Models\User;
use Carbon\Carbon;

class SendPregnancyWeeklyNotification extends Command
{
    protected $signature   = 'pregnancy:weekly-notify';
    protected $description = 'Send weekly pregnancy update notifications to users';

    private function getBabyData(int $week): array
    {
        $data = [
            1  => ['size' => 'ek microscopic cell', 'weight' => '<1g',   'emoji' => '🌱', 'development' => 'Fertilization ho rahi hai'],
            2  => ['size' => 'ek poppy seed',        'weight' => '<1g',   'emoji' => '🌿', 'development' => 'Implantation ho rahi hai'],
            3  => ['size' => 'ek sesame seed',        'weight' => '<1g',   'emoji' => '🫘', 'development' => 'Brain aur spinal cord banna shuru'],
            4  => ['size' => 'ek poppy seed',         'weight' => '<1g',   'emoji' => '🫛', 'development' => 'Dil ki dhadkan shuru ho rahi hai'],
            5  => ['size' => 'ek til ke barabar',     'weight' => '<1g',   'emoji' => '🫐', 'development' => 'Aankhein aur naak ban rahi hain'],
            6  => ['size' => 'ek matar ke barabar',   'weight' => '<1g',   'emoji' => '🍃', 'development' => 'Ungliyaan banna shuru ho rahi hain'],
            7  => ['size' => 'ek blueberry',          'weight' => '1g',    'emoji' => '🫐', 'development' => 'Haath aur pair nikal rahe hain'],
            8  => ['size' => 'ek rajma ke barabar',   'weight' => '1g',    'emoji' => '🫘', 'development' => 'Sare andar ke organs ban rahe hain'],
            9  => ['size' => 'ek angoor',              'weight' => '2g',    'emoji' => '🍇', 'development' => 'Muscles develop ho rahi hain'],
            10 => ['size' => 'ek strawberry',          'weight' => '4g',    'emoji' => '🍓', 'development' => 'Nails aur baal aa rahe hain'],
            11 => ['size' => 'ek nimbu',               'weight' => '7g',    'emoji' => '🍋', 'development' => 'Baby hilna shuru kar sakta hai'],
            12 => ['size' => 'ek aloo bukhaare',       'weight' => '14g',   'emoji' => '🍑', 'development' => 'Pahla trimester khatam! Sabhi organs ban gaye'],
            13 => ['size' => 'ek aadu',                'weight' => '23g',   'emoji' => '🍑', 'development' => 'Baby ke fingerprints ban rahe hain'],
            14 => ['size' => 'ek nimbu',               'weight' => '43g',   'emoji' => '🍋', 'development' => 'Baby chehra bana raha hai'],
            15 => ['size' => 'ek seb',                 'weight' => '70g',   'emoji' => '🍎', 'development' => 'Baby awaazein sun sakta hai'],
            16 => ['size' => 'ek avocado',             'weight' => '100g',  'emoji' => '🥑', 'development' => 'Baby aankhein hila sakta hai'],
            17 => ['size' => 'ek nashpati',            'weight' => '140g',  'emoji' => '🍐', 'development' => 'Baby ko sapne aa sakte hain'],
            18 => ['size' => 'ek shimla mirch',        'weight' => '190g',  'emoji' => '🫑', 'development' => 'Baby ko aapki awaaz sunai deti hai'],
            19 => ['size' => 'ek aam',                 'weight' => '240g',  'emoji' => '🥭', 'development' => 'Baby kick karna shuru karta hai'],
            20 => ['size' => 'ek kela',                'weight' => '300g',  'emoji' => '🍌', 'development' => 'Aadhi pregnancy complete! Baby hilta hai'],
            21 => ['size' => 'ek gajar',               'weight' => '360g',  'emoji' => '🥕', 'development' => 'Baby sucking practice karta hai'],
            22 => ['size' => 'ek papaya',              'weight' => '430g',  'emoji' => '🍈', 'development' => 'Baby ke features clear ho rahe hain'],
            23 => ['size' => 'ek grapefruit',          'weight' => '500g',  'emoji' => '🍊', 'development' => 'Lungs develop ho rahe hain'],
            24 => ['size' => 'ek makka',               'weight' => '600g',  'emoji' => '🌽', 'development' => 'Baby baahar ki awaazein sun sakta hai'],
            25 => ['size' => 'ek gobhi',               'weight' => '660g',  'emoji' => '🥦', 'development' => 'Haath ki pakad mazboot ho rahi hai'],
            26 => ['size' => 'ek salad patta',         'weight' => '760g',  'emoji' => '🥬', 'development' => 'Aankhein khulna shuru'],
            27 => ['size' => 'ek baingan',             'weight' => '875g',  'emoji' => '🍆', 'development' => 'Teesra trimester shuru! Brain fast grow ho raha hai'],
            28 => ['size' => 'ek baingan',             'weight' => '1kg',   'emoji' => '🍆', 'development' => 'Baby sapne dekhta hai'],
            29 => ['size' => 'ek kaddu',               'weight' => '1.2kg', 'emoji' => '🎃', 'development' => 'Haddiyan aur muscles strong ho rahi hain'],
            30 => ['size' => 'ek badi gobhi',          'weight' => '1.3kg', 'emoji' => '🥬', 'development' => 'Baby bahut active hai'],
            31 => ['size' => 'ek nariyal',             'weight' => '1.5kg', 'emoji' => '🥥', 'development' => 'Baby light dekhna shuru karta hai'],
            32 => ['size' => 'ek kaddu',               'weight' => '1.7kg', 'emoji' => '🎃', 'development' => 'Baby ka position fix ho raha hai'],
            33 => ['size' => 'ek ananas',              'weight' => '1.9kg', 'emoji' => '🍍', 'development' => 'Bones hard ho rahi hain'],
            34 => ['size' => 'ek kharbooza',           'weight' => '2.1kg', 'emoji' => '🍈', 'development' => 'Nervous system complete ho raha hai'],
            35 => ['size' => 'ek honeydew',            'weight' => '2.4kg', 'emoji' => '🍈', 'development' => 'Kidneys fully developed'],
            36 => ['size' => 'ek bada papaya',         'weight' => '2.6kg', 'emoji' => '🍈', 'development' => 'Baby janam ke liye taiyaar ho raha hai'],
            37 => ['size' => 'ek tarbuz',              'weight' => '2.9kg', 'emoji' => '🍉', 'development' => 'Baby full term! Kisi bhi din aa sakta hai'],
            38 => ['size' => 'ek kaddu',               'weight' => '3.1kg', 'emoji' => '🎃', 'development' => 'Baby ka weight badh raha hai'],
            39 => ['size' => 'ek bada tarbuz',         'weight' => '3.3kg', 'emoji' => '🍉', 'development' => 'Baby bilkul taiyaar hai'],
            40 => ['size' => 'ek chhota kaddu',        'weight' => '3.5kg', 'emoji' => '🎃', 'development' => 'Due date! Baby ka intezaar karein'],
        ];
        return $data[$week] ?? $data[40];
    }

    // Doctor visit & checkup reminders by week
    private function getCheckupReminder(int $week): ?array
    {
        $reminders = [
            8  => ['emoji' => '🔬', 'msg' => 'Pehla prenatal checkup aur blood tests karwayein. Doctor se milna zaroori hai!'],
            10 => ['emoji' => '🧪', 'msg' => 'First trimester screening (NT scan) karwayein. Baby ki health check karein!'],
            12 => ['emoji' => '📋', 'msg' => 'Pehla major ultrasound! Doctor appointment le lein aaj hi.'],
            16 => ['emoji' => '💉', 'msg' => 'Routine blood test aur urine test ka samay aa gaya hai. Doctor se milein!'],
            18 => ['emoji' => '👶', 'msg' => 'Anatomy scan (20 week scan) ki taiyari karein. Baby ka poora jaayza hoga!'],
            20 => ['emoji' => '🏥', 'msg' => 'Anatomy ultrasound karwayein — baby ke sare andar ke organs check honge!'],
            24 => ['emoji' => '🩸', 'msg' => 'Glucose tolerance test ka samay! Gestational diabetes check karwayein.'],
            28 => ['emoji' => '💊', 'msg' => 'Third trimester shuru! Doctor se iron aur calcium supplements ke baare mein poochein.'],
            32 => ['emoji' => '🔍', 'msg' => 'Baby ki position check karwayein. Growth ultrasound ka samay aa gaya!'],
            36 => ['emoji' => '🏨', 'msg' => 'Hospital bag taiyaar karein! Doctor se birth plan discuss karein. Har hafte checkup karwayein.'],
            38 => ['emoji' => '⚡', 'msg' => 'Har hafte doctor se milein. Labour ke signs pe dhyan rakhein — contractions, water break!'],
            40 => ['emoji' => '🚨', 'msg' => 'Due date aa gayi! Turant doctor se milein. Hospital ke liye taiyaar rahein!'],
        ];
        return $reminders[$week] ?? null;
    }

    private function getDietTip(int $week): ?array
    {
        $diets = [
            4  => ['emoji' => '🥗', 'msg' => 'Folic acid bahut zaroori hai! Palak, methi, daal aur anda khayein. Alcohol aur smoking bilkul band karein.'],
            6  => ['emoji' => '🍊', 'msg' => 'Vitamin C ke liye narangi, amla, nimbu lein. Ginger tea se morning sickness mein aram milega.'],
            8  => ['emoji' => '🥛', 'msg' => 'Calcium ke liye doodh, dahi, paneer roz khayein. Ek glass doodh subah zaroor piyein.'],
            10 => ['emoji' => '🥦', 'msg' => 'Iron ke liye palak, chukander, pomegranate lein. Vitamin C ke saath lene se iron zyada absorb hota hai.'],
            12 => ['emoji' => '🍳', 'msg' => 'Protein ke liye anda, daal, rajma, soya roz khayein. Baby ki growth ke liye protein sabse zaroori hai.'],
            14 => ['emoji' => '🐟', 'msg' => 'Omega-3 ke liye akhrot, flaxseed, fish (low mercury) khayein. Baby ke brain development ke liye bahut acha hai.'],
            16 => ['emoji' => '🧀', 'msg' => 'Calcium aur Vitamin D dono chahiye! Dhoop mein 15 min zaroor baithein. Paneer aur dahi khayein.'],
            18 => ['emoji' => '🌾', 'msg' => 'Fiber ke liye gehun ki roti, oats, brown rice khayein. Constipation se bachne ke liye khoob paani piyein.'],
            20 => ['emoji' => '🥩', 'msg' => 'Iron aur protein dono chahiye. Chicken, mutton (limited), daal, chane roz khayein. Anaemia se bachein.'],
            22 => ['emoji' => '🍌', 'msg' => 'Potassium ke liye kela, aalu, coconut water lein. Leg cramps mein potassium bahut help karta hai.'],
            24 => ['emoji' => '🚫🍬', 'msg' => 'Meetha aur maida kam karein! Gestational diabetes se bachne ke liye sugar avoid karein. Salad aur fruits khayein.'],
            26 => ['emoji' => '💧', 'msg' => 'Roz kam se kam 8-10 glass paani piyein. Coconut water, nimbu paani, chaas bahut faydemand hai.'],
            28 => ['emoji' => '🫘', 'msg' => 'Iron ki zyada zaroorat hai ab! Roz palak, rajma, masoor daal khayein. Iron supplement doctor ki salah se lein.'],
            30 => ['emoji' => '🥜', 'msg' => 'Healthy fats ke liye akhrot, badam, kaju roz 5-6 khayein. Baby ke brain ke liye bahut zaroori hai.'],
            32 => ['emoji' => '🍚', 'msg' => 'Chhote chhote meals lein — din mein 5-6 baar. Ek baar zyada khane se acidity hogi. Halka khana khayein.'],
            34 => ['emoji' => '🧂', 'msg' => 'Namak kam karein! Swelling aur BP control ke liye sodium kam rakhein. Fresh fruits aur vegetables khayein.'],
            36 => ['emoji' => '🫐', 'msg' => 'Antioxidants ke liye ber, angoor, blueberry khayein. Energy ke liye dates (khajoor) bahut acha hai delivery ke liye.'],
            38 => ['emoji' => '🍯', 'msg' => 'Dates (khajoor) roz 6-7 khayein — research kehti hai labour aasan hoti hai. Coconut water aur fruit juice piyein.'],
            40 => ['emoji' => '⚡', 'msg' => 'Halka aur easily digestible khana khayein. Sooji, daliya, khichdi achha rahega. Hydrated rahein — paani zyada piyein.'],
        ];
        return $diets[$week] ?? null;
    }

    public function handle()
    {
        $records = PregnancyTracking::with('user')->get();
        $sent = 0;

        foreach ($records as $record) {
            $user = $record->user;
            if (!$user || !$user->fcm_token) continue;

            $lmp  = Carbon::parse($record->lmp_date);
            $days = $lmp->diffInDays(Carbon::now());
            $week = (int) floor($days / 7);

            if ($week < 1 || $week > 40) continue;

            // Sirf weekly anniversary pe bhejo (har 7 din mein)
            if ($days % 7 !== 0) continue;

            // 1. Weekly baby update notification
            $baby  = $this->getBabyData($week);
            $title = "{$baby['emoji']} Week {$week} — Aapka Baby!";
            $body  = "Aapka shishu {$days} din ka ho gaya hai! "
                   . "Abhi woh {$baby['size']} jaisa bada hai aur lagbhag {$baby['weight']} ka hai. "
                   . "{$baby['development']}. 💕";

            FirebaseNotification::send($user->fcm_token, $title, $body, [
                'type' => 'pregnancy_weekly',
                'week' => (string) $week,
                'days' => (string) $days,
            ]);
            $sent++;

            // 2. Doctor visit / checkup reminder (agar is week koi reminder hai)
            $reminder = $this->getCheckupReminder($week);
            if ($reminder) {
                FirebaseNotification::send($user->fcm_token,
                    "{$reminder['emoji']} Week {$week} — Doctor Checkup Reminder!",
                    $reminder['msg'],
                    ['type' => 'pregnancy_checkup', 'week' => (string) $week]
                );
                $sent++;
            }
            // 3. Diet tip notification (har 2 week mein)
            if ($week % 2 === 0) {
                $diet = $this->getDietTip($week);
                if ($diet) {
                    FirebaseNotification::send($user->fcm_token,
                        "{$diet['emoji']} Week {$week} — Aaj Ka Diet Plan!",
                        $diet['msg'],
                        ['type' => 'pregnancy_diet', 'week' => (string) $week]
                    );
                    $sent++;
                }
            }
        }

        $this->info("Pregnancy notifications sent: {$sent}");
    }
}
