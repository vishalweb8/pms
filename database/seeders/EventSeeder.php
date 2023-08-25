<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'event_name' => 'Conferences',
                'event_date' => '31-01-2008',
                'description' => 'A conference is generally a meeting around a certain topic with many guests in attendance'
            ],[
                'event_name' => 'Seminars',
                'event_date' => '01-10-2009',
                'description' => 'In a seminar, the audiovisual technology plays an important role in the event planning process'
            ],[
                'event_name' => 'Team Building Events',
                'event_date' => '12-12-2012',
                'description' => 'Team building events can mean a range of things from fun and entertaining to learning and development events where the team brainstorm processes for the business'
            ],[
                'event_name' => 'Trade Shows / Expos',
                'event_date' => '12-12-2012',
                'description' => 'A trade show is all about the industry, not the business itself'
            ],[
                'event_name' => 'Corporate Dinners',
                'event_date' => '13-11-2011',
                'description' => 'A business or corporate dinner would be for anything from new hires in the business to celebrating milestones or networking with people in the industry'
            ],[
                'event_name' => 'Product Launches',
                'event_date' => '3-10-2020',
                'description' => 'Of course, if you have a new product coming to the market, you want as many people as possible to know about it'
            ],[
                'event_name' => 'Shareholder / Corporate Board Meetings',
                'event_date' => '31-11-2019',
                'description' => 'In a corporate setting, usually, the board and shareholders will meet up to discuss business for development and improvement'
            ],[
                'event_name' => 'Year-End Functions/parties',
                'event_date' => '12-12-2011',
                'description' => 'Celebrating team members is a great way to thank them for all their hard work'
            ],[
                'event_name' => 'Workshops/courses',
                'event_date' => '15-14-2013',
                'description' => 'If you are a business who educates others. Workshops and short courses are a great way to add extra layers to your business'
            ],[
                'event_name' => 'Birthdays',
                'event_date' => '13-10-2021',
                'description' => 'Businesses like to celebrate success'
            ],[
                'event_name' => 'Charity events',
                'event_date' => '11-11-2011',
                'description' => 'Charity events play an important role in how businesses interact with the community'
            ],[
                'event_name' => 'Networking events',
                'event_date' => '12-10-2016',
                'description' => 'These are large or medium-sized gatherings to allow people from employees to clients and potential clients to get to know each other'
            ]
        ]);
    }
}
