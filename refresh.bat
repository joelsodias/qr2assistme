@echo off

echo starting refresh database
php spark migrate:refresh

echo starting seed table 'customer' 
php spark db:seed CustomerSeeder 

echo starting seed table 'worker' 
php spark db:seed WorkerSeeder 

echo starting seed 'chat_user' table
php spark db:seed ChatUserSeeder 

echo starting seed table 'chat_session' 
php spark db:seed ChatSessionSeeder 

echo starting seed Customer table 'qrobject' 
php spark db:seed QrObjectSeeder 

echo starting seed Customer table 'scheduler' 
php spark db:seed ScheduleSeeder 


 