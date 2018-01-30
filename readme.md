ARM MANAGER 

Start creating an alias for the path where you clone it from github:
 ln -s /user/path/arm_php/projects/arm_manager  arm
 
 
 Now access your http://localhost/arm  and you will see a config error
 
 
 If you access http://localhost/arm/manager you will see a permission error
 
 
 Give 777 recursive permission on your temp folder at arm_manager/temp/  (your project temp_folder)
 
 chmod -R 777 /user/path/arm_php/projects/arm_managerolder
