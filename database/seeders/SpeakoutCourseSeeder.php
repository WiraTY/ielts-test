<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SpeakoutCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear existing data
        DB::table('lesson_speaking')->truncate();
        DB::table('lesson_audio')->truncate();
        DB::table('lessons')->truncate();
        DB::table('courses')->truncate();
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        
        // Course levels mapping
        $courseLevels = [
            'Starter' => 'starter',
            'Elementary' => 'elementary',
            'Pre-Intermediate' => 'pre-intermediate',
            'Intermediate' => 'intermediate',
            'Upper Intermediate' => 'upper-intermediate',
            'Advanced' => 'advanced'
        ];
        
        // Course data
        $courses = [
            [
                'title' => 'Speakout Starter',
                'level' => 'starter',
                'description' => 'Beginner English course for absolute beginners. Learn basic greetings, introductions, numbers, family, daily routines, food, time, hobbies, descriptions, shopping, directions, weather, and seasons.'
            ],
            [
                'title' => 'Speakout Elementary',
                'level' => 'elementary',
                'description' => 'Elementary English course for those with basic knowledge. Cover everyday life routines, jobs, free time activities, food and restaurants, places in town, travel, shopping, weather and clothes, past experiences, holidays, and future plans.'
            ],
            [
                'title' => 'Speakout Pre-Intermediate',
                'level' => 'pre-intermediate',
                'description' => 'Pre-intermediate English course expanding communication skills. Topics include meeting people, life stories, describing people, daily routines, travel and holidays, food and cooking, shopping and money, work and education, technology and media, health and lifestyle, and future plans.'
            ],
            [
                'title' => 'Speakout Intermediate',
                'level' => 'intermediate',
                'description' => 'Intermediate English course for developing fluency. Explore personal experiences, daily life routines, entertainment and media, travel and adventure, health and fitness, jobs and careers, shopping and services, technology and communication, society and culture, environment and nature, and future plans.'
            ],
            [
                'title' => 'Speakout Upper Intermediate',
                'level' => 'upper-intermediate',
                'description' => 'Upper intermediate English course for advanced communication. Topics include identity and personality, travel and discovery, work and ambitions, education and learning, media and communication, society and lifestyles, health and wellbeing, nature and environment, crime and justice, technology and innovation, art and creativity.'
            ],
            [
                'title' => 'Speakout Advanced',
                'level' => 'advanced',
                'description' => 'Advanced English course for sophisticated communication. Cover advanced communication skills, global issues, advanced storytelling, academic writing, business and economics, media and society, innovation and ethics, culture and identity, science and society, philosophy and critical thinking, advanced negotiation.'
            ]
        ];
        
        // Insert courses and collect their IDs
        $courseIds = [];
        foreach ($courses as $index => $courseData) {
            $courseData['slug'] = Str::slug($courseData['title']);
            $courseData['is_trial'] = true;
            $courseData['order'] = $index * 10;
            $courseData['created_by'] = 1; // Assuming admin user ID is 1
            $courseData['created_at'] = now();
            $courseData['updated_at'] = now();
            
            $courseId = DB::table('courses')->insertGetId($courseData);
            $courseIds[$courseData['level']] = $courseId;
        }
        
        // Lesson data for each course with full content
        $courseContent = [
            // Starter (A1) Lessons
            'starter' => [
                [
                    'title' => 'Greetings & Introductions',
                    'content' => "🎯 Learning Objectives

Bisa menyapa orang dalam berbagai situasi (pagi, siang, sore).

Bisa memperkenalkan diri dengan benar.

Bisa menanggapi perkenalan orang lain.

📖 Materi
Greetings (Salam): Hello, Hi, Good morning, Good afternoon, Good evening, Goodbye
Introductions: What’s your name?, My name is…, I’m…, Nice to meet you

Dialog Contoh:
👩 A: Hello! What’s your name?
👨 B: Hi! My name is Andi. Nice to meet you.
👩 A: Nice to meet you too.

📝 Grammar Focus

I am → I’m

You are → You’re

He/She is → He’s / She’s",
                    'listening_practice' => "Dengarkan dialog.

Ulangi 2 kali.

Pause, lalu coba ucapkan sendiri.",
                    'speaking_practice' => "Hello, my name is [nama kamu].

Nice to meet you."
                ],
                [
                    'title' => 'Numbers & Countries',
                    'content' => "🎯 Learning Objectives

Menggunakan angka 1–20.

Menanyakan dan menyebutkan asal negara.

📖 Materi
Numbers: 1–20
Countries & Nationalities: Indonesia/Indonesian, Japan/Japanese, USA/American, UK/British, Australia/Australian
Question: Where are you from? → I’m from Indonesia.

📝 Grammar Focus

Wh- questions: Where + to be",
                    'listening_practice' => "Dengarkan orang menyebutkan angka dan negara.",
                    'speaking_practice' => "I’m from [your country].

Where are you from?"
                ],
                [
                    'title' => 'Family & Friends',
                    'content' => "🎯 Learning Objectives

Mengenal kosakata keluarga.

Menggunakan possessive ’s.

📖 Materi
Family: father, mother, brother, sister, friend, parents, son, daughter
Pattern: This is my brother. / This is Maria’s mother.

📝 Grammar Focus

Possessive ’s → Maria’s brother",
                    'listening_practice' => "Dengarkan perkenalan keluarga.",
                    'speaking_practice' => "This is my [family member]."
                ],
                [
                    'title' => 'Daily Routines',
                    'content' => "🎯 Learning Objectives

Menyebutkan aktivitas harian.

Menggunakan present simple.

📖 Materi
Activities: wake up, go to school, eat breakfast, study, watch TV, sleep
Question: What time do you wake up?

📝 Grammar Focus

Present simple: I wake up at 7. She wakes up at 7.",
                    'listening_practice' => "Dengarkan rutinitas harian orang lain.",
                    'speaking_practice' => "I wake up at ___."
                ],
                [
                    'title' => 'Food & Drinks',
                    'content' => "🎯 Learning Objectives

Menyebut makanan/minuman.

Menyatakan suka/tidak suka.

📖 Materi
Food: rice, bread, chicken, fish
Drinks: water, tea, coffee, juice
Expressions: I like…, I don’t like…

📝 Grammar Focus

Like / Don’t like + noun",
                    'listening_practice' => "Dengarkan orang menyebut makanan favorit.",
                    'speaking_practice' => "I like [food]. I don’t like [food]."
                ],
                [
                    'title' => 'Time & Days',
                    'content' => "🎯 Learning Objectives

Menyebutkan jam dan hari.

Bertanya tentang waktu.

📖 Materi
Days: Monday–Sunday
Time: What time is it? → It’s 3 o’clock.

📝 Grammar Focus

It’s + time",
                    'listening_practice' => "Dengarkan pengumuman waktu.",
                    'speaking_practice' => "It’s __ o’clock."
                ],
                [
                    'title' => 'Hobbies & Free Time',
                    'content' => "🎯 Learning Objectives

Bicara tentang hobi.

Menggunakan like + verb-ing.

📖 Materi
Hobbies: reading, swimming, playing football, watching movies
Expression: I like swimming.

📝 Grammar Focus

Like + verb-ing",
                    'listening_practice' => "Dengarkan orang bicara tentang hobinya.",
                    'speaking_practice' => "I like [verb-ing]."
                ],
                [
                    'title' => 'Describing People',
                    'content' => "🎯 Learning Objectives

Mendeskripsikan penampilan orang.

Menggunakan adjective.

📖 Materi
Adjectives: tall, short, young, old, beautiful, handsome
Pattern: She is tall. He is young.

📝 Grammar Focus

to be + adjective",
                    'listening_practice' => "Dengarkan deskripsi orang.",
                    'speaking_practice' => "My friend is ___."
                ],
                [
                    'title' => 'Shopping',
                    'content' => "🎯 Learning Objectives

Menyebutkan barang.

Menanyakan harga.

📖 Materi
Items: book, pen, bag, shoes, shirt
Expression: How much is it? → It’s $5.

📝 Grammar Focus

How much + is/are",
                    'listening_practice' => "Dengarkan percakapan di toko.",
                    'speaking_practice' => "How much is this?"
                ],
                [
                    'title' => 'Around Town / Directions',
                    'content' => "🎯 Learning Objectives

Mengenal tempat di kota.

Bertanya arah jalan.

📖 Materi
Places: bank, school, park, supermarket, hospital
Expressions: Where is the bank? Go straight, turn left, turn right.

📝 Grammar Focus

Where is + place?",
                    'listening_practice' => "Dengarkan instruksi arah.",
                    'speaking_practice' => "Where is the __?"
                ],
                [
                    'title' => 'Weather & Seasons',
                    'content' => "🎯 Learning Objectives

Menyebutkan cuaca.

Bicara tentang musim.

📖 Materi
Weather: sunny, rainy, cloudy, windy, hot, cold
Seasons: summer, winter, rainy season
Pattern: It’s sunny today.

📝 Grammar Focus

It’s + adjective (weather)",
                    'listening_practice' => "Dengarkan ramalan cuaca.",
                    'speaking_practice' => "It’s [weather] today."
                ],
                [
                    'title' => 'Review & Consolidation',
                    'content' => "🎯 Learning Objectives

Mengulang materi 1–11.

Berlatih percakapan lengkap.

📖 Materi

Greetings & Introductions

Numbers & Countries

Family, Daily routines, Food, Hobbies

Describing, Shopping, Directions, Weather

📝 Grammar Focus

Review to be, present simple, like + ing, possessive ’s",
                    'listening_practice' => "Dengarkan percakapan gabungan (perkenalan, hobi, belanja).",
                    'speaking_practice' => "Simulasi percakapan sehari-hari."
                ]
            ],
            
            // Elementary (A2) Lessons
            'elementary' => [
                [
                    'title' => 'Everyday Life',
                    'content' => "🎯 Learning Objectives

Bicara tentang aktivitas sehari-hari.

Menanyakan rutinitas orang lain.

📖 Materi
Activities: get up, have breakfast, go to work, have lunch, finish work, go home, watch TV, go to bed
Questions: What time do you get up? Do you go to work by bus?

📝 Grammar Focus

Present Simple: he/she + -s → She gets up at 7.

Questions with do/does",
                    'listening_practice' => "Dengarkan orang menceritakan rutinitasnya.",
                    'speaking_practice' => "I get up at ___.

Do you watch TV at night?"
                ],
                [
                    'title' => 'Work and Jobs',
                    'content' => "🎯 Learning Objectives

Menyebutkan pekerjaan.

Menjelaskan apa yang dilakukan seseorang.

📖 Materi
Jobs: teacher, student, doctor, engineer, driver, nurse, waiter
Expressions: What do you do? I’m a student.

📝 Grammar Focus

a/an + job (I’m a doctor.)",
                    'listening_practice' => "Dengarkan orang memperkenalkan pekerjaan mereka.",
                    'speaking_practice' => "What do you do?

I’m a [job]."
                ],
                [
                    'title' => 'Free Time',
                    'content' => "🎯 Learning Objectives

Bicara tentang kegiatan di waktu luang.

Menyatakan suka/tidak suka.

📖 Materi
Activities: go shopping, play football, listen to music, read books, surf the internet
Expressions: I love…, I like…, I don’t like…

📝 Grammar Focus

Like/love + verb-ing",
                    'listening_practice' => "Dengarkan orang bicara tentang hobi.",
                    'speaking_practice' => "I like [activity].

Do you like reading?"
                ],
                [
                    'title' => 'Food & Restaurants',
                    'content' => "🎯 Learning Objectives

Menyebutkan makanan/minuman.

Memesan makanan di restoran.

📖 Materi
Food & Drinks: rice, chicken, salad, water, juice, coffee
Expressions: Can I have…, please?

📝 Grammar Focus

Countable/uncountable nouns (a sandwich, some rice)",
                    'listening_practice' => "Dengarkan orang memesan makanan.",
                    'speaking_practice' => "Can I have a coffee, please?"
                ],
                [
                    'title' => 'Places in Town',
                    'content' => "🎯 Learning Objectives

Menyebutkan tempat umum.

Memberi dan bertanya arah.

📖 Materi
Places: post office, bus station, supermarket, bank, cinema, restaurant
Expressions: Where’s the bank? It’s next to the supermarket.

📝 Grammar Focus

Prepositions of place: next to, opposite, between",
                    'listening_practice' => "Dengarkan orang bertanya arah.",
                    'speaking_practice' => "Where’s the __?

It’s next to the __."
                ],
                [
                    'title' => 'Travel',
                    'content' => "🎯 Learning Objectives

Bicara tentang transportasi dan perjalanan.

Menanyakan jadwal transportasi.

📖 Materi
Transport: bus, train, taxi, airplane, bicycle
Expressions: How do you go to school? By bus.

📝 Grammar Focus

By + transport (by bus, by car)",
                    'listening_practice' => "Dengarkan orang bicara tentang perjalanan.",
                    'speaking_practice' => "I go to work by ___."
                ],
                [
                    'title' => 'Daily Shopping',
                    'content' => "🎯 Learning Objectives

Berbelanja kebutuhan sehari-hari.

Menanyakan harga barang.

📖 Materi
Items: bread, milk, eggs, fruit, vegetables
Expressions: How much is it? It’s $2.

📝 Grammar Focus

How much + is/are",
                    'listening_practice' => "Dengarkan percakapan di toko.",
                    'speaking_practice' => "How much is this?"
                ],
                [
                    'title' => 'Weather & Clothes',
                    'content' => "🎯 Learning Objectives

Bicara tentang cuaca dan pakaian.

Menyatakan preferensi berpakaian sesuai cuaca.

📖 Materi
Weather: sunny, rainy, cold, hot
Clothes: shirt, trousers, dress, coat, shoes
Expressions: It’s cold. I’m wearing a coat.

📝 Grammar Focus

Present continuous: I’m wearing a ___.",
                    'listening_practice' => "Dengarkan laporan cuaca.",
                    'speaking_practice' => "Today it’s ___.

I’m wearing ___."
                ],
                [
                    'title' => 'Past Experiences',
                    'content' => "🎯 Learning Objectives

Menceritakan pengalaman di masa lalu.

Menggunakan past simple.

📖 Materi
Verbs (past): went, saw, ate, had, visited
Expressions: Last weekend I went to the beach.

📝 Grammar Focus

Past simple: verb + -ed / irregular verbs",
                    'listening_practice' => "Dengarkan cerita akhir pekan seseorang.",
                    'speaking_practice' => "Last weekend I ___."
                ],
                [
                    'title' => 'Holidays',
                    'content' => "🎯 Learning Objectives

Bicara tentang liburan.

Menyebutkan kegiatan liburan favorit.

📖 Materi
Activities: swimming, sightseeing, relaxing, taking photos
Expressions: I went to Bali. It was great!

📝 Grammar Focus

Past simple with was/were",
                    'listening_practice' => "Dengarkan orang bercerita tentang liburannya.",
                    'speaking_practice' => "Where did you go on holiday?

I went to ___."
                ],
                [
                    'title' => 'Future Plans',
                    'content' => "🎯 Learning Objectives

Bicara tentang rencana masa depan.

Menggunakan going to.

📖 Materi
Expressions: I’m going to visit my friend. He’s going to study tomorrow.

📝 Grammar Focus

be + going to + verb",
                    'listening_practice' => "Dengarkan orang bicara tentang rencananya.",
                    'speaking_practice' => "I’m going to ___."
                ],
                [
                    'title' => 'Review & Consolidation',
                    'content' => "🎯 Learning Objectives

Mengulang materi Lessons 1–11.

Membuat percakapan lebih panjang.

📖 Materi

Routines, Jobs, Free time, Food, Places, Travel, Shopping, Weather, Past experiences, Future plans

📝 Grammar Focus

Review: Present simple, Past simple, Going to",
                    'listening_practice' => "Dengarkan percakapan gabungan (rutinitas + liburan + rencana).",
                    'speaking_practice' => "Ceritakan sehari-hari, pengalaman, dan rencana ke depan."
                ]
            ],
            
            // Pre-Intermediate (A2-B1) Lessons
            'pre-intermediate' => [
                [
                    'title' => 'Meeting People',
                    'content' => "🎯 Learning Objectives

Memperluas perkenalan diri.

Bicara tentang asal, pekerjaan, dan hobi.

📖 Materi
Expressions: Where are you from? What do you do? What are your hobbies?

📝 Grammar Focus

Question forms: Where/What/Who/When

Short answers: Yes, I am. / No, I’m not.",
                    'listening_practice' => "Dengarkan percakapan dua orang baru kenal.",
                    'speaking_practice' => "I’m from ___.

I work as a ___.

My hobby is ___."
                ],
                [
                    'title' => 'Life Stories',
                    'content' => "🎯 Learning Objectives

Menceritakan pengalaman masa lalu.

Menggunakan past simple dengan lancar.

📖 Materi
Verbs (past): studied, lived, worked, traveled, met
Expressions: I lived in Jakarta for 2 years.

📝 Grammar Focus

Past simple: regular & irregular verbs

Time expressions: last year, in 2010, two weeks ago",
                    'listening_practice' => "Dengarkan seseorang bercerita tentang hidupnya.",
                    'speaking_practice' => "I traveled to ___ last year."
                ],
                [
                    'title' => 'Describing People',
                    'content' => "🎯 Learning Objectives

Mendeskripsikan penampilan fisik dan kepribadian.

📖 Materi
Appearance: tall, short, slim, curly hair, blue eyes
Personality: friendly, shy, talkative, funny
Expressions: He is tall and friendly.

📝 Grammar Focus

be + adjective

and / but to connect ideas",
                    'listening_practice' => "Dengarkan deskripsi orang.",
                    'speaking_practice' => "My friend is ___.

He/She has ___."
                ],
                [
                    'title' => 'Daily Routines & Time',
                    'content' => "🎯 Learning Objectives

Bicara lebih detail tentang rutinitas dengan waktu tertentu.

📖 Materi
Expressions: I usually wake up at 7 a.m. I often go jogging in the morning.

📝 Grammar Focus

Adverbs of frequency: always, usually, often, sometimes, never",
                    'listening_practice' => "Dengarkan orang bicara tentang jadwal harian.",
                    'speaking_practice' => "I usually ___.

I sometimes ___."
                ],
                [
                    'title' => 'Travel & Holidays',
                    'content' => "🎯 Learning Objectives

Bicara tentang perjalanan, transportasi, dan pengalaman liburan.

📖 Materi
Transport: ferry, underground, tram
Expressions: How did you travel? Where did you stay?

📝 Grammar Focus

Past simple questions: Did you go by train? Yes, I did.",
                    'listening_practice' => "Dengarkan percakapan orang tentang liburan mereka.",
                    'speaking_practice' => "I went to ___ by ___."
                ],
                [
                    'title' => 'Food & Cooking',
                    'content' => "🎯 Learning Objectives

Bicara tentang makanan favorit dan memasak.

📖 Materi
Food: pasta, soup, fish, fruit salad
Cooking verbs: boil, fry, bake, grill
Expressions: I like grilled fish.

📝 Grammar Focus

Countable/uncountable nouns (a banana, some soup)",
                    'listening_practice' => "Dengarkan resep sederhana.",
                    'speaking_practice' => "My favorite food is ___.

I usually cook ___."
                ],
                [
                    'title' => 'Shopping & Money',
                    'content' => "🎯 Learning Objectives

Membeli barang dan menanyakan harga.

📖 Materi
Expressions: How much is this? Can I try it on? Do you have it in blue?

📝 Grammar Focus

Comparative adjectives: cheaper, more expensive",
                    'listening_practice' => "Dengarkan percakapan di toko pakaian.",
                    'speaking_practice' => "This shirt is cheaper than that one."
                ],
                [
                    'title' => 'Work & Education',
                    'content' => "🎯 Learning Objectives

Bicara tentang pekerjaan, sekolah, dan belajar.

📖 Materi
Expressions: I work in a bank. I study at university. I’m interested in science.

📝 Grammar Focus

Present continuous for temporary actions: I’m studying English now.",
                    'listening_practice' => "Dengarkan orang bercerita tentang pekerjaannya.",
                    'speaking_practice' => "I’m working/studying ___."
                ],
                [
                    'title' => 'Technology & Media',
                    'content' => "🎯 Learning Objectives

Bicara tentang penggunaan teknologi sehari-hari.

📖 Materi
Words: computer, smartphone, internet, social media
Expressions: I use my phone to check emails.

📝 Grammar Focus

Verb + to + infinitive (I use my phone to play music.)",
                    'listening_practice' => "Dengarkan orang bicara tentang gadgetnya.",
                    'speaking_practice' => "I use my ___ to ___."
                ],
                [
                    'title' => 'Health & Lifestyle',
                    'content' => "🎯 Learning Objectives

Bicara tentang kesehatan, olahraga, dan gaya hidup.

📖 Materi
Words: exercise, healthy food, sleep, stress
Expressions: I try to eat healthy food.

📝 Grammar Focus

Should/shouldn’t: You should eat more vegetables.",
                    'listening_practice' => "Dengarkan saran kesehatan.",
                    'speaking_practice' => "You should ___.

You shouldn’t ___."
                ],
                [
                    'title' => 'Future Plans & Ambitions',
                    'content' => "🎯 Learning Objectives

Bicara tentang rencana masa depan dengan detail.

📖 Materi
Expressions: I’m going to travel. I’d like to study abroad.

📝 Grammar Focus

be going to / would like to",
                    'listening_practice' => "Dengarkan orang membicarakan ambisinya.",
                    'speaking_practice' => "I’m going to ___.

I’d like to ___."
                ],
                [
                    'title' => 'Review & Project',
                    'content' => "🎯 Learning Objectives

Mengulang materi Lessons 1–11.

Membuat presentasi mini tentang diri sendiri.

📖 Materi

Perkenalan, rutinitas, makanan, liburan, pekerjaan, teknologi, rencana masa depan.

📝 Grammar Focus

Review: Past simple, Present continuous, Going to",
                    'listening_practice' => "Dengarkan percakapan campuran.",
                    'speaking_practice' => "Buat presentasi tentang hidupmu, hobi, pengalaman, dan rencanamu."
                ]
            ],
            
            // Intermediate (B1) Lessons
            'intermediate' => [
                [
                    'title' => 'Personal Experiences',
                    'content' => "🎯 Learning Objectives

Bisa menceritakan pengalaman pribadi dengan detail.

Menggunakan past simple & present perfect dengan benar.

📖 Materi
Expressions: I’ve been to Bali. I went there last year. It was amazing.

📝 Grammar Focus

Present perfect vs past simple

ever/never, already/yet",
                    'listening_practice' => "Dengarkan orang membicarakan pengalaman liburannya.",
                    'speaking_practice' => "Have you ever ___?

Yes, I have. / No, I haven’t."
                ],
                [
                    'title' => 'Daily Life & Routines',
                    'content' => "🎯 Learning Objectives

Bicara lebih detail tentang kebiasaan dan rutinitas.

📖 Materi
Expressions: I usually wake up at 6, but I’m working late this week.

📝 Grammar Focus

Present simple vs present continuous

State verbs vs action verbs",
                    'listening_practice' => "Dengarkan deskripsi rutinitas seseorang.",
                    'speaking_practice' => "I usually ___.

Right now, I’m ___."
                ],
                [
                    'title' => 'Entertainment & Media',
                    'content' => "🎯 Learning Objectives

Bicara tentang film, musik, dan acara TV favorit.

📖 Materi
Expressions: I love watching comedies. I’m really into pop music.

📝 Grammar Focus

Gerunds & infinitives: like/love + doing, want + to do",
                    'listening_practice' => "Dengarkan orang bicara tentang film yang mereka suka.",
                    'speaking_practice' => "I like watching ___.

I want to see ___."
                ],
                [
                    'title' => 'Travel & Adventure',
                    'content' => "🎯 Learning Objectives

Bicara tentang perjalanan, transportasi, dan petualangan.

📖 Materi
Expressions: I’d love to go backpacking. Traveling by train is comfortable.

📝 Grammar Focus

Comparatives & superlatives

as … as, not as … as",
                    'listening_practice' => "Dengarkan percakapan tentang liburan backpacking.",
                    'speaking_practice' => "Traveling by plane is faster than ___."
                ],
                [
                    'title' => 'Health & Fitness',
                    'content' => "🎯 Learning Objectives

Bicara tentang gaya hidup sehat dan kebugaran.

📖 Materi
Expressions: I try to exercise three times a week. You should eat more fruit.

📝 Grammar Focus

Should/shouldn’t, must/mustn’t",
                    'listening_practice' => "Dengarkan saran dari dokter tentang kesehatan.",
                    'speaking_practice' => "You should ___.

You mustn’t ___."
                ],
                [
                    'title' => 'Jobs & Careers',
                    'content' => "🎯 Learning Objectives

Bicara tentang pekerjaan, karier, dan pengalaman kerja.

📖 Materi
Expressions: I applied for a job. I work as an engineer.

📝 Grammar Focus

Past continuous (I was working when …)

Used to (I used to work in a shop.)",
                    'listening_practice' => "Dengarkan orang bercerita tentang kariernya.",
                    'speaking_practice' => "I used to ___.

When I was working at ___, I ___."
                ],
                [
                    'title' => 'Shopping & Services',
                    'content' => "🎯 Learning Objectives

Bicara tentang pengalaman belanja dan layanan.

📖 Materi
Expressions: Could I try this on? Do you have this in size M?

📝 Grammar Focus

Polite requests: Could, Would",
                    'listening_practice' => "Dengarkan percakapan di toko.",
                    'speaking_practice' => "Could I have ___, please?"
                ],
                [
                    'title' => 'Technology & Communication',
                    'content' => "🎯 Learning Objectives

Bicara tentang penggunaan teknologi dan komunikasi.

📖 Materi
Expressions: I use my phone to check social media.

📝 Grammar Focus

Relative clauses: The app that I use is very popular.",
                    'listening_practice' => "Dengarkan orang bicara tentang gadget favoritnya.",
                    'speaking_practice' => "The ___ that I use most is ___."
                ],
                [
                    'title' => 'Society & Culture',
                    'content' => "🎯 Learning Objectives

Bicara tentang tradisi, kebiasaan, dan budaya.

📖 Materi
Expressions: In my culture, people greet by shaking hands.

📝 Grammar Focus

Present perfect continuous: I’ve been learning English for 3 years.",
                    'listening_practice' => "Dengarkan orang bercerita tentang kebudayaan negaranya.",
                    'speaking_practice' => "In my culture, we usually ___."
                ],
                [
                    'title' => 'Environment & Nature',
                    'content' => "🎯 Learning Objectives

Bicara tentang lingkungan, alam, dan isu global.

📖 Materi
Expressions: We should recycle more. Pollution is a big problem.

📝 Grammar Focus

First conditional (If + present, will + base verb)",
                    'listening_practice' => "Dengarkan percakapan tentang lingkungan.",
                    'speaking_practice' => "If we recycle, we will ___."
                ],
                [
                    'title' => 'Future Plans & Predictions',
                    'content' => "🎯 Learning Objectives

Bicara tentang prediksi dan rencana masa depan.

📖 Materi
Expressions: I’m going to travel next year. Life will be different in 2050.

📝 Grammar Focus

Going to vs will",
                    'listening_practice' => "Dengarkan prediksi tentang teknologi masa depan.",
                    'speaking_practice' => "I’m going to ___.

People will ___."
                ],
                [
                    'title' => 'Review & Project',
                    'content' => "🎯 Learning Objectives

Mengulang semua materi Intermediate.

Membuat presentasi singkat tentang “My Life and My Future”.

📖 Materi

Pengalaman, rutinitas, hiburan, pekerjaan, teknologi, lingkungan, masa depan.

📝 Grammar Focus

Mixed tenses review (past, present, future)",
                    'listening_practice' => "Dengarkan percakapan review.",
                    'speaking_practice' => "Presentasikan cerita hidupmu + rencanamu di masa depan."
                ]
            ],
            
            // Upper Intermediate (B2) Lessons
            'upper-intermediate' => [
                [
                    'title' => 'Identity & Personality',
                    'content' => "🎯 Learning Objectives

Mendeskripsikan karakter dan identitas seseorang secara detail.

Menggunakan kata sifat lanjutan untuk kepribadian.

📖 Materi
Words: ambitious, reliable, outgoing, introverted, generous
Expressions: She comes across as friendly. He seems ambitious.

📝 Grammar Focus

Seem / appear / come across as

Adjective + preposition (good at, interested in, keen on)",
                    'listening_practice' => "Dengarkan deskripsi karakter tokoh publik.",
                    'speaking_practice' => "My best friend seems ___.

I’m really interested in ___."
                ],
                [
                    'title' => 'Travel & Discovery',
                    'content' => "🎯 Learning Objectives

Membicarakan pengalaman perjalanan tak terlupakan.

Menggunakan narrative tenses dengan baik.

📖 Materi
Expressions: I had never seen such a place before. We were driving when the storm started.

📝 Grammar Focus

Past simple, past continuous, past perfect (narrative tenses)",
                    'listening_practice' => "Dengarkan cerita perjalanan menegangkan.",
                    'speaking_practice' => "Tell a story about a surprising trip."
                ],
                [
                    'title' => 'Work & Ambitions',
                    'content' => "🎯 Learning Objectives

Membicarakan karier, ambisi, dan pencapaian profesional.

📖 Materi
Expressions: I aim to become a manager. She managed to finish on time.

📝 Grammar Focus

Verb patterns: aim to, manage to, succeed in + -ing",
                    'listening_practice' => "Dengarkan wawancara tentang ambisi karier.",
                    'speaking_practice' => "I aim to ___.

I succeeded in ___."
                ],
                [
                    'title' => 'Education & Learning',
                    'content' => "🎯 Learning Objectives

Bicara tentang gaya belajar dan sistem pendidikan.

📖 Materi
Expressions: I prefer learning by doing. The course focuses on communication.

📝 Grammar Focus

Gerunds after prepositions: interested in learning, good at speaking",
                    'listening_practice' => "Dengarkan siswa berbicara tentang gaya belajar mereka.",
                    'speaking_practice' => "I’m good at ___.

I prefer ___."
                ],
                [
                    'title' => 'Media & Communication',
                    'content' => "🎯 Learning Objectives

Membicarakan berita, media, dan komunikasi modern.

📖 Materi
Expressions: The news was broadcast live. Social media influences people.

📝 Grammar Focus

Passive voice (present & past): English is spoken worldwide.",
                    'listening_practice' => "Dengarkan berita singkat.",
                    'speaking_practice' => "This app is used by millions."
                ],
                [
                    'title' => 'Society & Lifestyles',
                    'content' => "🎯 Learning Objectives

Membicarakan perubahan gaya hidup dan masyarakat.

📖 Materi
Expressions: People tend to live longer now. Society is becoming more diverse.

📝 Grammar Focus

Present continuous for change (is becoming, are getting)",
                    'listening_practice' => "Dengarkan percakapan tentang perubahan gaya hidup.",
                    'speaking_practice' => "People are becoming more ___."
                ],
                [
                    'title' => 'Health & Wellbeing',
                    'content' => "🎯 Learning Objectives

Bicara tentang kesehatan mental & fisik.

📖 Materi
Expressions: Stress can lead to illness. Exercise helps reduce anxiety.

📝 Grammar Focus

Modals of deduction: must, might, can’t",
                    'listening_practice' => "Dengarkan saran kesehatan dari ahli.",
                    'speaking_practice' => "He must be tired.

It might be serious."
                ],
                [
                    'title' => 'Nature & Environment',
                    'content' => "🎯 Learning Objectives

Bicara tentang masalah lingkungan global.

📖 Materi
Expressions: Deforestation affects climate change. We need sustainable energy.

📝 Grammar Focus

Second conditional: If I were rich, I would donate more.",
                    'listening_practice' => "Dengarkan diskusi tentang solusi lingkungan.",
                    'speaking_practice' => "If I were a leader, I would ___."
                ],
                [
                    'title' => 'Crime & Justice',
                    'content' => "🎯 Learning Objectives

Membicarakan kejahatan, hukum, dan keadilan.

📖 Materi
Expressions: He was accused of theft. The trial lasted two weeks.

📝 Grammar Focus

Reported speech (The police said he had stolen a car.)",
                    'listening_practice' => "Dengarkan berita kriminal.",
                    'speaking_practice' => "The witness said ___."
                ],
                [
                    'title' => 'Technology & Innovation',
                    'content' => "🎯 Learning Objectives

Membicarakan teknologi masa depan dan inovasi.

📖 Materi
Expressions: Artificial intelligence will transform jobs.

📝 Grammar Focus

Future continuous & future perfect

I will be working at 9. / By 2050, cars will have changed.",
                    'listening_practice' => "Dengarkan prediksi tentang teknologi.",
                    'speaking_practice' => "In 10 years, I will be ___.

By 2050, people will have ___."
                ],
                [
                    'title' => 'Art & Creativity',
                    'content' => "🎯 Learning Objectives

Membicarakan seni, desain, dan kreativitas.

📖 Materi
Expressions: The exhibition was fascinating. Creativity is essential.

📝 Grammar Focus

Cleft sentences: What I love is … / The thing that inspires me is …",
                    'listening_practice' => "Dengarkan review seni.",
                    'speaking_practice' => "What I enjoy most is ___."
                ],
                [
                    'title' => 'Review & Final Project',
                    'content' => "🎯 Learning Objectives

Menggabungkan semua topik B2.

Membuat presentasi argumentatif.

📖 Materi

Identitas, perjalanan, karier, teknologi, lingkungan, seni.

📝 Grammar Focus

Mixed conditionals, narrative tenses, modals, future perfect.",
                    'listening_practice' => "Dengarkan debat singkat.",
                    'speaking_practice' => "Presentasikan opini tentang \"The Future of Our World\"."
                ]
            ],
            
            // Advanced (C1) Lessons
            'advanced' => [
                [
                    'title' => 'Advanced Communication',
                    'content' => "🎯 Learning Objectives

Menguasai idiom & collocation untuk komunikasi tingkat lanjut.

Menggunakan bahasa formal & informal sesuai konteks.

📖 Materi
Idioms: break the ice, get straight to the point, beat around the bush
Collocations: effective communication, mutual understanding

📝 Grammar Focus

Inversion for emphasis: Rarely have I seen such talent.",
                    'listening_practice' => "Dengarkan debat formal vs casual chat.",
                    'speaking_practice' => "Break the ice with your partner.

Use inversion: \"Never have I felt so …\""
                ],
                [
                    'title' => 'Global Issues',
                    'content' => "🎯 Learning Objectives

Berdiskusi tentang isu dunia (kemiskinan, migrasi, perubahan iklim).

Membuat argumen meyakinkan.

📖 Materi
Expressions: It raises concerns about … / One solution could be …

📝 Grammar Focus

Hedging & cautious language: It seems that … / It could be argued that …",
                    'listening_practice' => "Dengarkan wawancara aktivis lingkungan.",
                    'speaking_practice' => "It could be argued that ….

One possible solution is …."
                ],
                [
                    'title' => 'Advanced Storytelling',
                    'content' => "🎯 Learning Objectives

Menceritakan kisah hidup dengan gaya naratif kompleks.

📖 Materi
Expressions: Looking back, in hindsight, little did I know …

📝 Grammar Focus

Mixed conditionals: If I had studied harder, I would be a doctor now.",
                    'listening_practice' => "Dengarkan cerita pribadi inspiratif.",
                    'speaking_practice' => "Tell your biggest turning point story."
                ],
                [
                    'title' => 'Academic Writing Skills',
                    'content' => "🎯 Learning Objectives

Menguasai struktur essay argumentatif.

📖 Materi
Linkers: moreover, however, in contrast, on the other hand

📝 Grammar Focus

Nominalisation: decide → decision, fail → failure",
                    'listening_practice' => "Dengarkan kuliah singkat tentang essay structure.",
                    'speaking_practice' => "Present a mini-argument using linking words."
                ],
                [
                    'title' => 'Business & Economics',
                    'content' => "🎯 Learning Objectives

Membicarakan tren ekonomi & bisnis global.

📖 Materi
Expressions: supply and demand, economic downturn, booming industry

📝 Grammar Focus

Future perfect continuous: By 2030, people will have been working remotely for decades.",
                    'listening_practice' => "Dengarkan laporan ekonomi.",
                    'speaking_practice' => "By 2030, I will have been ___."
                ],
                [
                    'title' => 'Media & Society',
                    'content' => "🎯 Learning Objectives

Menganalisis pengaruh media terhadap opini publik.

📖 Materi
Expressions: media bias, fake news, mass influence

📝 Grammar Focus

Emphatic structures: What concerns me most is …",
                    'listening_practice' => "Dengarkan analisis berita.",
                    'speaking_practice' => "What bothers me most is …."
                ],
                [
                    'title' => 'Innovation & Ethics',
                    'content' => "🎯 Learning Objectives

Diskusi kritis tentang etika teknologi baru.

📖 Materi
Expressions: ethical dilemma, unintended consequences, cutting-edge

📝 Grammar Focus

Complex conditionals: If technology were regulated, it might have prevented problems.",
                    'listening_practice' => "Dengarkan debat tentang AI.",
                    'speaking_practice' => "If I were a policymaker, I would …."
                ],
                [
                    'title' => 'Culture & Identity',
                    'content' => "🎯 Learning Objectives

Mengeksplorasi identitas budaya & globalisasi.

📖 Materi
Expressions: cultural heritage, clash of cultures, sense of belonging

📝 Grammar Focus

Advanced relative clauses: which/that/whose/whom",
                    'listening_practice' => "Dengarkan wawancara tentang identitas budaya.",
                    'speaking_practice' => "My culture, which …, is unique because …."
                ],
                [
                    'title' => 'Science & Society',
                    'content' => "🎯 Learning Objectives

Diskusi peran sains dalam kehidupan modern.

📖 Materi
Expressions: scientific breakthrough, evidence-based, research findings

📝 Grammar Focus

Passive with reporting verbs: It is believed that … / He is said to …",
                    'listening_practice' => "Dengarkan berita penelitian.",
                    'speaking_practice' => "It is thought that …."
                ],
                [
                    'title' => 'Philosophy & Critical Thinking',
                    'content' => "🎯 Learning Objectives

Melatih berpikir kritis & menyusun argumen filosofis.

📖 Materi
Expressions: It raises the question of …, on a deeper level, thought-provoking

📝 Grammar Focus

Subjunctive mood: It is essential that he be present.",
                    'listening_practice' => "Dengarkan diskusi filsafat.",
                    'speaking_practice' => "It is essential that …."
                ],
                [
                    'title' => 'Advanced Negotiation',
                    'content' => "🎯 Learning Objectives

Menguasai bahasa untuk negosiasi kompleks.

📖 Materi
Expressions: reach a compromise, reject an offer, strike a deal

📝 Grammar Focus

Advanced conditionals in negotiation: Unless we agree, the deal will fail.",
                    'listening_practice' => "Dengarkan simulasi negosiasi bisnis.",
                    'speaking_practice' => "Unless we …, we won’t …."
                ],
                [
                    'title' => 'Final Debate & Reflection',
                    'content' => "🎯 Learning Objectives

Menggabungkan semua skill C1.

Melakukan debat formal dengan argumen kritis.

📖 Materi

Semua tema: komunikasi, global issues, sains, etika, budaya.

📝 Grammar Focus

Semua struktur advanced: inversion, mixed conditionals, subjunctive, nominalisation.",
                    'listening_practice' => "Dengarkan potongan debat universitas.",
                    'speaking_practice' => "Debat topik: Technology brings more harm than good."
                ]
            ]
        ];
        
        // Insert lessons for each course
        foreach ($courseContent as $level => $courseLessons) {
            $courseId = $courseIds[$level];
            
            foreach ($courseLessons as $lessonIndex => $lessonData) {
                // Insert lesson
                $lessonId = DB::table('lessons')->insertGetId([
                    'course_id' => $courseId,
                    'slug' => Str::slug($courseId . '-' . $lessonData['title']),
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'],
                    'order' => $lessonIndex,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Insert lesson audio practice
                DB::table('lesson_audio')->insert([
                    'lesson_id' => $lessonId,
                    'description' => $lessonData['listening_practice'],
                    'is_enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Insert lesson speaking practice
                DB::table('lesson_speaking')->insert([
                    'lesson_id' => $lessonId,
                    'description' => $lessonData['speaking_practice'],
                    'is_enabled' => true,
                    'duration' => 120, // Default 2 minutes
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        $this->command->info('Speakout courses, lessons, and practice content seeded successfully!');
    }
}