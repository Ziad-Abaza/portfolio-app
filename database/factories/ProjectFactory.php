<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(3, true);
        $slug = Str::slug($title);
        
        $projects = [
            'E-Commerce Platform' => 'منصة تجارة إلكترونية',
            'Social Media Dashboard' => 'لوحة تحكم وسائل التواصل الاجتماعي',
            'Task Management System' => 'نظام إدارة المهام',
            'Weather Application' => 'تطبيق الطقس',
            'Blog Platform' => 'منصة مدونة',
            'Portfolio Website' => 'موقع معرض أعمال',
            'Chat Application' => 'تطبيق دردشة',
            'Analytics Dashboard' => 'لوحة تحكم التحليلات',
        ];
        
        $projectTitle = $this->faker->randomElement(array_keys($projects));
        $projectTitleAr = $projects[$projectTitle];
        
        $categories = ['web', 'ai', 'iot', 'mobile', 'desktop'];
        $category = $this->faker->randomElement($categories);
        
        $technologies = [
            'web' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'MySQL', 'Redis'],
            'ai' => ['Python', 'TensorFlow', 'PyTorch', 'Scikit-learn', 'OpenCV'],
            'iot' => ['Arduino', 'Raspberry Pi', 'ESP32', 'MQTT', 'Node-RED'],
            'mobile' => ['React Native', 'Flutter', 'Firebase', 'GraphQL'],
            'desktop' => ['Electron', 'React', 'Node.js', 'SQLite'],
        ];
        
        $challenges = [
            'web' => ['Scalability', 'Real-time Updates', 'Security', 'Performance'],
            'ai' => ['Data Quality', 'Model Accuracy', 'Training Time', 'Interpretability'],
            'iot' => ['Connectivity', 'Power Management', 'Data Processing', 'Security'],
            'mobile' => ['Offline Support', 'Performance', 'Battery Life', 'Cross-platform'],
            'desktop' => ['Cross-platform Compatibility', 'File Management', 'Memory Usage', 'Updates'],
        ];
        
        $solutions = [
            'web' => ['Microservices Architecture', 'WebSocket Implementation', 'OAuth 2.0', 'CDN Integration'],
            'ai' => ['Data Cleaning Pipeline', 'Ensemble Methods', 'Transfer Learning', 'Model Explainability'],
            'iot' => ['MQTT Protocol', 'Low-power Design', 'Edge Computing', 'End-to-end Encryption'],
            'mobile' => ['Local Storage', 'Code Splitting', 'Background Processing', 'Native Modules'],
            'desktop' => ['Electron Framework', 'IndexedDB', 'Memory Optimization', 'Auto-updater'],
        ];
        
        return [
            'slug' => Str::slug($projectTitle),
            'category' => $category,
            'is_featured' => $this->faker->boolean(30), // 30% chance of being featured
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'completed_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'title_en' => $projectTitle,
            'description_en' => $this->faker->paragraph(3),
            'content_en' => $this->faker->paragraphs(5, true),
            'technologies_en' => $technologies[$category],
            'challenges_en' => $challenges[$category],
            'solutions_en' => $solutions[$category],
            'title_ar' => $projectTitleAr,
            'description_ar' => $this->faker->paragraph(3),
            'content_ar' => $this->faker->paragraphs(5, true),
            'technologies_ar' => $technologies[$category],
            'challenges_ar' => $challenges[$category],
            'solutions_ar' => $solutions[$category],
            'github_url' => $this->faker->optional(0.8)->url(), // 80% chance of having GitHub URL
            'live_url' => $this->faker->optional(0.6)->url(), // 60% chance of having live URL
            'demo_url' => $this->faker->optional(0.4)->url(), // 40% chance of having demo URL
            'images' => $this->faker->randomElements([
                $this->faker->imageUrl(800, 600, 'technology'),
                $this->faker->imageUrl(800, 600, 'business'),
                $this->faker->imageUrl(800, 600, 'abstract'),
            ], $this->faker->numberBetween(1, 3)),
            'thumbnail_url' => $this->faker->imageUrl(400, 300, 'technology'),
            'tags' => $this->faker->randomElements([
                'innovative', 'modern', 'scalable', 'user-friendly', 'secure',
                'fast', 'responsive', 'mobile-first', 'cloud-based', 'ai-powered'
            ], $this->faker->numberBetween(2, 5)),
            'metadata' => [
                'client' => $this->faker->company(),
                'duration' => $this->faker->numberBetween(1, 12) . ' months',
                'team_size' => $this->faker->numberBetween(1, 10),
                'budget' => '$' . $this->faker->numberBetween(1000, 100000),
            ],
        ];
    }
}
