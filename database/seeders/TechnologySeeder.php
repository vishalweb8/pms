<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('technology')->insert([
            [
            'technology' => 'Elasticsearch',
            'description' => 'Elasticsearch is a search engine based on the Lucene library. It provides a distributed, multitenant-capable full-text search engine with an HTTP web interface and schema-free JSON documents.'
            ],[
                'technology' => 'Apache Solr',
                'description' => 'Solr is an open-source enterprise-search platform, written in Java, from the Apache Lucene project. Its major features include full-text search, hit highlighting, faceted search, real-time indexing, dynamic clustering, database integration, NoSQL features and rich document handling.'
            ],[
                'technology' => 'Node.js',
                'description' => 'Solr is an open-source enterprise-search platform, written in Java, from the Apache Lucene project. Its major features include full-text search, hit highlighting, faceted search, real-time indexing, dynamic clustering, database integration, NoSQL features and rich document handling.'
            ],[
                'technology' => 'PHP',
                'description' => 'PHP is a general-purpose scripting language especially suited to web development. It was originally created by Danish-Canadian programmer Rasmus Lerdorf in 1994.'
            ],[
                'technology' => 'Java',
                'description' => 'Java is a class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.'
            ],[
                'technology' => 'Python',
                'description' => 'Python is an interpreted, high-level and general-purpose programming language. Pythons design philosophy emphasizes code readability with its notable use of significant indentation.'
            ],[
                'technology' => 'Swift',
                'description' => 'Swift is a general-purpose, multi-paradigm, compiled programming language developed by Apple Inc. and the open-source community, first released in 2014.'
            ],[
                'technology' => 'React Native',
                'description' => 'React Native is an open-source mobile application framework created by Facebook, Inc. It is used to develop applications for Android, Android TV, iOS, macOS, tvOS, Web, Windows and UWP by enabling developers to use Reacts framework along with native platform capabilities.'
            ],[
                'technology' => 'Flutter',
                'description' => 'Flutter is an open-source UI software development kit created by Google. It is used to develop applications for Android, iOS, Linux, Mac, Windows, Google Fuchsia, and the web from a single codebase.'
            ],[
                'technology' => 'Ionic',
                'description' => 'Ionic is a complete open-source SDK for hybrid mobile app development created by Max Lynch, Ben Sperry, and Adam Bradley of Drifty Co. in 2013.'
            ],[
                'technology' => 'Angular',
                'description' => 'Angular is a TypeScript-based open-source web application framework led by the Angular Team at Google and by a community of individuals and corporations.'
            ],[
                'technology' => 'React',
                'description' => 'React is an open-source, front end, JavaScript library for building user interfaces or UI components. It is maintained by Facebook and a community of individual developers and companies. React can be used as a base in the development of single-page or mobile applications.'
            ],[
                'technology' => 'Vue.js',
                'description' => 'Vue.js is an open-source model–view–viewmodel front end JavaScript framework for building user interfaces and single-page applications.'
            ],[
                'technology' => 'TypeScript',
                'description' => 'TypeScript is a programming language developed and maintained by Microsoft. It is a strict syntactical superset of JavaScript and adds optional static typing to the language. TypeScript is designed for the development of large applications and transcompiles to JavaScript.'
            ],[
                'technology' => 'Magento',
                'description' => 'Magento is an open-source e-commerce platform written in PHP. It uses multiple other PHP frameworks such as Laminas and Symfony.'
            ],[
                'technology' => 'Shopify',
                'description' => 'Shopify Inc. is a Canadian multinational e-commerce company headquartered in Ottawa, Ontario. It is also the name of its proprietary e-commerce platform for online stores and retail point-of-sale systems.'
            ],[
                'technology' => 'SAP Hybris',
                'description' => 'SAP Hybris solutions were integrated under the SAP masterbrand into the wider SAP Customer Experience portfolio'
            ],[
                'technology' => 'Liferay',
                'description' => 'Build portals, intranets, websites and connected experiences on the most extensible digital experience platform around.'
            ],[
                'technology' => 'WordPress',
                'description' => 'WordPress is a free and open-source content management system written in PHP and paired with a MySQL or MariaDB database. Features include a plugin architecture and a template system, referred to within WordPress as Themes.'
            ],[
                'technology' => 'Umbraco',
                'description' => 'Umbraco is an open-source content management system platform for publishing content on the World Wide Web and intranets. It is written in C# and deployed on Microsoft based infrastructure.'
            ],[
                'technology' => 'Drupal',
                'description' => 'Drupal is a free and open-source web content management framework written in PHP and distributed under the GNU General Public License. Drupal provides a back-end framework for at least 12% of the top 10,000 websites worldwide – ranging from personal blogs to corporate, political, and government sites.'
            ],[
                'technology' => 'Amazon Web Services',
                'description' => 'Drupal is a free and open-source web content management framework written in PHP and distributed under the GNU General Public License. Drupal provides a back-end framework for at least 12% of the top 10,000 websites worldwide – ranging from personal blogs to corporate, political, and government sites.'
            ],[
                'technology' => 'Google Cloud Platform',
                'description' => 'Google Cloud Platform, offered by Google, is a suite of cloud computing services that runs on the same infrastructure that Google uses internally for its end-user products, such as Google Search, Gmail, file storage, and YouTube.'
            ],[
                'technology' => 'Microsoft Azure',
                'description' => 'Microsoft Azure, commonly referred to as Azure, is a cloud computing service created by Microsoft for building, testing, deploying, and managing applications and services through Microsoft-managed data centers.'
            ],[
                'technology' => 'Selenium',
                'description' => 'Selenium is a portable framework for testing web applications. Selenium provides a playback tool for authoring functional tests without the need to learn a test scripting language.'
            ],[
                'technology' => 'MySQL',
                'description' => 'MySQL is an open-source relational database management system.'
            ],[
                'technology' => 'Amazon DynamoDB',
                'description' => 'Amazon DynamoDB is a fully managed proprietary NoSQL database service that supports key-value and document data structures and is offered by Amazon.com as part of the Amazon Web Services portfolio.'
            ],[
                'technology' => 'PostgreSQL',
                'description' => 'PostgreSQL, also known as Postgres, is a free and open-source relational database management system emphasizing extensibility and SQL compliance.'
            ],[
                'technology' => 'Oracle Database',
                'description' => 'Oracle Database is a multi-model database management system produced and marketed by Oracle Corporation. It is a database commonly used for running online transaction processing, data warehousing and mixed database workloads.'
            ],[
                'technology' => 'Firebase',
                'description' => 'Firebase is Googles mobile platform that helps you quickly develop high-quality apps and grow your business.'
            ],[
                'technology' => 'MongoDB',
                'description' => 'MongoDB is a source-available cross-platform document-oriented database program. Classified as a NoSQL database program, MongoDB uses JSON-like documents with optional schemas.'
            ]
        ]);
    }
}
