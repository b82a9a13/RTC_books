# RTC_books
- This is an accounting site for simple accounting on a ubuntu server. This is currently used for a small family business and is still in development

# Instructions
- You will need to add the tcpdf version 6.6.2 libary to the directory as a folder named tcpdf.
- You will need to run the ubuntu_install_stack.sh to install all the required packages.
- You may also want to install git if you are going to pull this repository instead of uploading it.
- You will need to also have a database tables setup, you can use the database.sql to create the database and tables.

# Features
- There is only a single account which is able to input data but currently deleteing or updating data has to be done via the database using SQL. In the future this will be done using the UI but I haven't gotten round to doing it yet, feel free to create a merge request if you want to add this in.
- You can also export the data in PDF format since this is required when submitting your taxes.
