<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(2, true);
        $slug = Str::slug($title);
        
        $services = [
            'Web Development' => 'تطوير الويب',
            'Mobile Development' => 'تطوير تطبيقات الجوال',
            'UI/UX Design' => 'تصميم واجهة المستخدم',
            'Database Design' => 'تصميم قواعد البيانات',
            'API Development' => 'تطوير واجهات برمجة التطبيقات',
            'Cloud Services' => 'خدمات سحابية',
            'DevOps' => 'DevOps',
            'Security Consulting' => 'استشارات أمنية',
        ];
        
        $serviceTitle = $this->faker->randomElement(array_keys($services));
        $serviceTitleAr = $services[$serviceTitle];
        
        $technologies = [
            'Web Development' => ['Laravel', 'Vue.js', 'React', 'Tailwind CSS', 'MySQL'],
            'Mobile Development' => ['React Native', 'Flutter', 'Swift', 'Kotlin'],
            'UI/UX Design' => ['Figma', 'Adobe XD', 'Sketch', 'Photoshop'],
            'Database Design' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis'],
            'API Development' => ['REST', 'GraphQL', 'Laravel', 'Node.js'],
            'Cloud Services' => ['AWS', 'Azure', 'Google Cloud', 'Docker'],
            'DevOps' => ['Docker', 'Kubernetes', 'Jenkins', 'GitLab CI'],
            'Security Consulting' => ['OWASP', 'SSL/TLS', 'Firewall', 'Penetration Testing'],
        ];
        
        $features = [
            'Web Development' => ['Responsive Design', 'RESTful APIs', 'Database Architecture', 'Performance Optimization'],
            'Mobile Development' => ['Cross-platform', 'Native Performance', 'Offline Support', 'Push Notifications'],
            'UI/UX Design' => ['User Research', 'Wireframing', 'Prototyping', 'Usability Testing'],
            'Database Design' => ['Schema Design', 'Query Optimization', 'Data Migration', 'Backup Strategies'],
            'API Development' => ['RESTful Design', 'Authentication', 'Rate Limiting', 'Documentation'],
            'Cloud Services' => ['Scalability', 'High Availability', 'Auto-scaling', 'Monitoring'],
            'DevOps' => ['CI/CD Pipeline', 'Infrastructure as Code', 'Containerization', 'Monitoring'],
            'Security Consulting' => ['Security Audits', 'Vulnerability Assessment', 'Compliance', 'Security Training'],
        ];
        
        $featuresAr = [
            'Web Development' => ['تصميم متجاوب', 'واجهات برمجة التطبيقات RESTful', 'هندسة قواعد البيانات', 'تحسين الأداء'],
            'Mobile Development' => ['متعدد المنصات', 'أداء أصلي', 'دعم بدون اتصال', 'الإشعارات الفورية'],
            'UI/UX Design' => ['بحث المستخدم', 'الإطارات الشبكية', 'النماذج الأولية', 'اختبار سهولة الاستخدام'],
            'Database Design' => ['تصميم المخطط', 'تحسين الاستعلامات', 'ترحيل البيانات', 'استراتيجيات النسخ الاحتياطي'],
            'API Development' => ['تصميم RESTful', 'المصادقة', 'تحديد المعدل', 'التوثيق'],
            'Cloud Services' => ['قابلية التوسع', 'التوافر العالي', 'التحجيم التلقائي', 'المراقبة'],
            'DevOps' => ['خط أنابيب CI/CD', 'البنية التحتية ككود', 'الحاوية', 'المراقبة'],
            'Security Consulting' => ['تدقيقات أمنية', 'تقييم الثغرات', 'الامتثال', 'التدريب الأمني'],
        ];
        
        return [
            'slug' => Str::slug($serviceTitle),
            'icon' => $this->faker->randomElement(['code', 'mobile', 'palette', 'database', 'api', 'cloud', 'server', 'shield']),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'title_en' => $serviceTitle,
            'description_en' => $this->faker->paragraph(3),
            'technologies_en' => $technologies[$serviceTitle],
            'title_ar' => $serviceTitleAr,
            'description_ar' => $this->faker->paragraph(3),
            'technologies_ar' => $technologies[$serviceTitle],
            'features_en' => $features[$serviceTitle],
            'features_ar' => $featuresAr[$serviceTitle],
            'image_url' => $this->faker->imageUrl(640, 480, 'business'),
            'is_active' => true,
        ];
    }
}
