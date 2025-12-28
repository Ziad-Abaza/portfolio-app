<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skills = [
            // Web Development
            'Laravel' => 'Laravel',
            'Vue.js' => 'Vue.js',
            'React' => 'React',
            'Tailwind CSS' => 'Tailwind CSS',
            'JavaScript' => 'JavaScript',
            'PHP' => 'PHP',
            'MySQL' => 'MySQL',
            'PostgreSQL' => 'PostgreSQL',
            
            // AI/ML
            'Python' => 'Python',
            'TensorFlow' => 'TensorFlow',
            'PyTorch' => 'PyTorch',
            'Scikit-learn' => 'Scikit-learn',
            'OpenCV' => 'OpenCV',
            'Machine Learning' => 'تعلم الآلة',
            'Deep Learning' => 'التعلم العميق',
            'Natural Language Processing' => 'معالجة اللغات الطبيعية',
            
            // IoT
            'Arduino' => 'Arduino',
            'Raspberry Pi' => 'Raspberry Pi',
            'ESP32' => 'ESP32',
            'MQTT' => 'MQTT',
            'Node-RED' => 'Node-RED',
            'Embedded Systems' => 'الأنظمة المدمجة',
            'Sensor Networks' => 'شبكات المستشعرات',
            
            // Cloud & DevOps
            'Docker' => 'Docker',
            'Kubernetes' => 'Kubernetes',
            'AWS' => 'AWS',
            'Azure' => 'Azure',
            'Google Cloud' => 'Google Cloud',
            'CI/CD' => 'CI/CD',
            'Linux' => 'Linux',
            'Git' => 'Git',
        ];
        
        $skillName = $this->faker->randomElement(array_keys($skills));
        $skillNameAr = $skills[$skillName];
        
        $categories = [
            'web_development' => ['Laravel', 'Vue.js', 'React', 'Tailwind CSS', 'JavaScript', 'PHP', 'MySQL', 'PostgreSQL'],
            'ai_ml' => ['Python', 'TensorFlow', 'PyTorch', 'Scikit-learn', 'OpenCV', 'Machine Learning', 'Deep Learning', 'Natural Language Processing'],
            'iot' => ['Arduino', 'Raspberry Pi', 'ESP32', 'MQTT', 'Node-RED', 'Embedded Systems', 'Sensor Networks'],
            'cloud_devops' => ['Docker', 'Kubernetes', 'AWS', 'Azure', 'Google Cloud', 'CI/CD', 'Linux', 'Git'],
        ];
        
        $category = 'web_development';
        foreach ($categories as $cat => $skillList) {
            if (in_array($skillName, $skillList)) {
                $category = $cat;
                break;
            }
        }
        
        $descriptions = [
            'Laravel' => 'PHP framework for web application development',
            'Vue.js' => 'Progressive JavaScript framework for building UIs',
            'React' => 'JavaScript library for building user interfaces',
            'Tailwind CSS' => 'Utility-first CSS framework for rapid UI development',
            'JavaScript' => 'Programming language for web development',
            'PHP' => 'Server-side scripting language for web development',
            'MySQL' => 'Relational database management system',
            'PostgreSQL' => 'Advanced relational database system',
            'Python' => 'Programming language for AI and data science',
            'TensorFlow' => 'Open-source machine learning framework',
            'PyTorch' => 'Machine learning framework for deep learning',
            'Scikit-learn' => 'Machine learning library for Python',
            'OpenCV' => 'Computer vision library',
            'Arduino' => 'Open-source electronics platform',
            'Raspberry Pi' => 'Single-board computer for IoT projects',
            'ESP32' => 'Microcontroller with Wi-Fi and Bluetooth',
            'MQTT' => 'Lightweight messaging protocol for IoT',
            'Node-RED' => 'Programming tool for wiring hardware devices',
            'Docker' => 'Platform for developing and running applications in containers',
            'Kubernetes' => 'Container orchestration platform',
            'AWS' => 'Amazon Web Services cloud platform',
            'Azure' => 'Microsoft cloud computing platform',
            'Google Cloud' => 'Google cloud computing platform',
            'CI/CD' => 'Continuous integration and continuous deployment',
            'Linux' => 'Open-source operating system',
            'Git' => 'Version control system',
        ];
        
        $descriptionsAr = [
            'Laravel' => 'إطار عمل PHP لتطوير تطبيقات الويب',
            'Vue.js' => 'إطار عمل JavaScript تقدمي لبناء واجهات المستخدم',
            'React' => 'مكتبة JavaScript لبناء واجهات المستخدم',
            'Tailwind CSS' => 'إطار عمل CSS للأداة الأولى لتطوير واجهة المستخدم بسرعة',
            'JavaScript' => 'لغة برمجة لتطوير الويب',
            'PHP' => 'لغة برمجة من جانب الخادم لتطوير الويب',
            'MySQL' => 'نظام إدارة قواعد البيانات العلائقية',
            'PostgreSQL' => 'نظام قاعدة بيانات علائقية متقدم',
            'Python' => 'لغة برمجة للذكاء الاصطناعي وعلم البيانات',
            'TensorFlow' => 'إطار عمل تعلم الآلة مفتوح المصدر',
            'PyTorch' => 'إطار عمل تعلم الآلة للتعلم العميق',
            'Scikit-learn' => 'مكتبة تعلم الآلة لـ Python',
            'OpenCV' => 'مكتبة رؤية الكمبيوتر',
            'Arduino' => 'منصة إلكترونيات مفتوحة المصدر',
            'Raspberry Pi' => 'كمبيوتر بلوحة واحدة لمشاريع إنترنت الأشياء',
            'ESP32' => 'متحكم دقيق مع Wi-Fi و Bluetooth',
            'MQTT' => 'بروتوكول مراسلة خفيف الوزن لإنترنت الأشياء',
            'Node-RED' => 'أداة برمجة لتوصيل أجهزة الأجهزة',
            'Docker' => 'منصة لتطوير وتشغيل التطبيقات في حاويات',
            'Kubernetes' => 'منصة تنسيق الحاويات',
            'AWS' => 'منصة سحابة Amazon Web Services',
            'Azure' => 'منصة الحوسبة السحابية من Microsoft',
            'Google Cloud' => 'منصة الحوسبة السحابية من Google',
            'CI/CD' => 'التكامل المستمر والنشر المستمر',
            'Linux' => 'نظام تشغيل مفتوح المصدر',
            'Git' => 'نظام التحكم في الإصدارات',
        ];
        
        return [
            'slug' => Str::slug($skillName),
            'category' => $category,
            'proficiency_level' => $this->faker->numberBetween(60, 95),
            'years_experience' => $this->faker->numberBetween(1, 5),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'name_en' => $skillName,
            'description_en' => $descriptions[$skillName] ?? $this->faker->sentence(),
            'keywords_en' => $this->faker->words(3, false),
            'certifications_en' => $this->faker->randomElements([
                'Certified Professional',
                'Expert Level',
                'Advanced Certification',
                'Master Certification',
            ], $this->faker->numberBetween(0, 2)),
            'name_ar' => $skillNameAr,
            'description_ar' => $descriptionsAr[$skillName] ?? $this->faker->sentence(),
            'keywords_ar' => $this->faker->words(3, false),
            'certifications_ar' => $this->faker->randomElements([
                'محترف معتمد',
                'مستوى خبير',
                'شهادة متقدمة',
                'شهادة ماجستير',
            ], $this->faker->numberBetween(0, 2)),
            'icon' => $this->faker->randomElement(['code', 'brain', 'microchip', 'cloud', 'database', 'server']),
            'color' => $this->faker->hexColor(),
            'projects_count' => [
                'total' => $this->faker->numberBetween(1, 20),
                'recent' => $this->faker->numberBetween(0, 5),
            ],
        ];
    }
}
