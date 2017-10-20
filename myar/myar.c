#include <fcntl.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <time.h>
#include <utime.h>
#include <ctype.h>
#include <ar.h>
#include <sys/stat.h>
#include <dirent.h>

/****************************************************************************************
 This program runs in a manner similar to the standard Unix command ar. The following is
 the syntax this program supports:
 
 myar key archive-file [member [...]]
 
 where archive-file is the name of the archive file to be used, and key is one of
 the following options:
 
 -q	Quickly append named files (members) to archive. 
	
 -x Extract named members. If no member is named on the command line when extracting
    files, all files are extracted from the archive.
 
 -t Print a concise table of contents of the archive. 
 
 -v Print a verbose table of contents of the archive. 
 
 -d Delete named files from archive. 
 
 -A Quickly append all “regular” files in the current directory. 
	 
 The options listed above are compatible with the options having the same name in the
 ar command, except for the following exceptions: the -v and -t command take no
 further argument, and list all files in the archive. -v is short for -t -v on the ar
 command. The -A command is a new option not in the usual ar command.
****************************************************************************************/

void extract_one_file(char *arch_file, char *dest_file) {
  int i;
  int r, w;
  int fa, fd;
  int fa_size, file_pos;
  char ch;
  char file_name[16];
  char file_mode[4];
  char file_size[10];

  //delete the file if it already exists
  unlink(dest_file);

  fa = open(arch_file, O_RDONLY);
  if (fa == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  fd = open(dest_file, O_WRONLY | O_CREAT, 0666);
  if (fd == -1) {
    perror("Can't create extract file");
    exit(EXIT_FAILURE);
  }

  //get the file size
  fa_size = lseek(fa, 0L, SEEK_END);

  //go to the beginning of the file
  file_pos = lseek(fa, 0L, SEEK_SET);

  //skip the first line
  file_pos = lseek(fa, SARMAG, SEEK_CUR);

  //start seeking through the file reading file names
  while (fa_size > file_pos) {

    //read the first file name into a char array
    read(fa, file_name, 16);
    i = 0;
    while (i < sizeof(file_name)) {
      if (file_name[i] == '/') {
	file_name[i] = '\0';
	break;
      };
      i ++;
    }

    //set the file permissions
    file_pos = lseek(fa, 42 - 16, SEEK_CUR);
    read(fa, file_mode, 4);
    chmod(file_name, strtol(file_mode, 0, 8));

    //get the file size
    file_pos = lseek(fa, 2, SEEK_CUR);
    read(fa, file_size, 10);

    //check to see if this is the file to be written
    if (strcmp(dest_file, file_name) == 0) {

      //write the file contents to the file
      file_pos = lseek(fa, 2, SEEK_CUR);
      i = 0;
      while (i < atoi(file_size)) {
	r = read(fa, &ch, sizeof(ch));
	if (r < 0) {
	  perror("Error while reading the source file");
	  exit(EXIT_FAILURE);
	}
	w = write(fd, &ch, sizeof(ch));
	if (w < 0) {
	  perror("Error while writing to destination file");
	  exit(EXIT_FAILURE);
	}
	i++;
      }

      //if we found the file & wrote the data, we are done
      break;
    }

    //otherwise, seek to the next file header
    if (atoi(file_size)%2 == 0)
      file_pos = lseek(fa, atoi(file_size) + 2, SEEK_CUR);
    else
      file_pos = lseek(fa, atoi(file_size) + 3, SEEK_CUR); 
  }

  close(fd);
  close(fa);
}

void extract_files(int *fd, char *args[]) {
  int fd_size;
  char file_name[16];
  char file_size[10];
  int file_pos;
  int i;
  int is_extract;

  if (*fd == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  //get the file size
  fd_size = lseek(*fd, 0L, SEEK_END);

  //go to the beginning of the file
  lseek(*fd, 0L, SEEK_SET);

  //skip the first line
  lseek(*fd, SARMAG, SEEK_CUR);

  //start seeking through the file reading file names
  while (fd_size > file_pos) {
    //initialize the extraction flag
    is_extract = 0;

    //read the first file name into a char array
    read(*fd, file_name, 16);
    i = 0;
    while (i < sizeof(file_name)) {
      if (file_name[i] == '/') {
	file_name[i] = '\0';
	break;
      };
      i ++;
    }
    
    //check to see if this file is to be extracted
    i = 3;
    while (args[i] != '\0') {      
      if (strcmp(args[i], file_name) == 0)
    	is_extract = 1;
      i++;
    }

    //if no files are passed, then extract all of them
    if (args[3] == '\0')
      is_extract = 1;

    //if it is to be extracted, extract the file
    if (is_extract) {
      extract_one_file(args[2], file_name);
    }

    //get the file size
    lseek(*fd, 48 - 16, SEEK_CUR);  
    read(*fd, file_size, 10);
  
    //seek to the next file header
    if (atoi(file_size)%2 == 0)
      file_pos = lseek(*fd, atoi(file_size) + 2, SEEK_CUR);
    else
      file_pos = lseek(*fd, atoi(file_size) + 3, SEEK_CUR);   
  }
}

void append_one_file(char *fd, char *p_file) {
  int j;
  int r, w;
  int count;
  int fs, fa;
  char ch;
  char file_name[16];
  char file_epoch[12];
  char file_owner[6];
  char file_group[6];
  char file_mode[8];
  char file_size[10];
  struct stat sd, s;
  struct tm tm;

  //open the source file
  fs = open(p_file, O_RDONLY);
  if (fs == -1) {
    perror("Can't open source file");
    exit(EXIT_FAILURE);
  }

  //open the destination file
  fa = open(fd, O_WRONLY | O_APPEND);
  if (fa == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  //collect the destination file stats
  stat(fd, &sd);

  //collect the source file stats
  stat(p_file, &s);

  if ((int) sd.st_size == 0)
    write(fa, ARMAG, SARMAG);

  //get the file name
  j = 0;
  while (j < sizeof(p_file)-1) {
    file_name[j] = p_file[j];
    j++;
  }
  file_name[j++] = '/';
  while (j < sizeof(file_name)) {
    file_name[j] = ' ';
    j++;
  }

  //get the file epoch time
  memset(&file_epoch[0], 0, sizeof(file_epoch));
  tm = *localtime(&s.st_mtime);
  strftime(file_epoch, sizeof(file_epoch), "%s", &tm);
  j = 0;
  while (j < 12) {
    if (file_epoch[j] == 0)
      file_epoch[j] = ' ';
    j++;
  }

  //get the file owner
  memset(&file_owner[0], 0, sizeof(file_owner));
  snprintf(file_owner, sizeof(file_owner), "%d", (int) s.st_uid);
  j = 0;
  while (j < sizeof(file_owner)) {
    if (file_owner[j] == 0)
      file_owner[j] = ' ';
    j++;
  }

  //get the file group
  memset(&file_group[0], 0, sizeof(file_group));
  snprintf(file_group, sizeof(file_group), "%d", (int) s.st_gid);
  j = 0;
  while (j < sizeof(file_group)) {
    if (file_group[j] == 0)
      file_group[j] = ' ';
    j++;
  }

  //get the file permissions
  memset(&file_mode[0], 0, sizeof(file_mode));
  snprintf(file_mode, sizeof(file_mode), "%o", s.st_mode);
  j = 0;
  while (j < sizeof(file_mode)) {
    if (file_mode[j] == 0)
      file_mode[j] = ' ';
    j++;
  }

  //get the file size
  memset(&file_size[0], 0, sizeof(file_size));
  snprintf(file_size, sizeof(file_size), "%d", (int) s.st_size);
  j = 0;
  while (j < sizeof(file_size)) {
    if (file_size[j] == 0)
      file_size[j] = ' ';
    j++;
  }

  //write the header to the archive
  write(fa, file_name, sizeof(file_name));
  write(fa, file_epoch, sizeof(file_epoch));
  write(fa, file_owner, sizeof(file_owner));
  write(fa, file_group, sizeof(file_group));
  write(fa, file_mode, sizeof(file_mode));
  write(fa, file_size, sizeof(file_size));
  write(fa, ARFMAG, sizeof(ARFMAG)-1);

  //write the file contents to the archive
  count = 0;
  while ((r = read(fs, &ch, sizeof(ch))) != 0) {
    if (r < 0) {
      perror("Error while reading the source file");
      exit(EXIT_FAILURE);
    }
    w = write(fa, &ch, sizeof(ch));
    if (w < 0) {
      perror("Error while writing to archive");
      exit(EXIT_FAILURE);
    }
    count++;
  }

  //add a new line for odd character files
  if (count%2 == 1) {
    write(fa, "\n", 1);
  }

  //close the source file, and move to the next argument
  close(fs);
}

void delete_files(int *fd, char *args[]) {  
  int fd_size;
  char file_name[16];
  char file_size[10];
  int file_pos;
  int i;
  int is_delete;

  if (*fd == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  //get the file size
  fd_size = lseek(*fd, 0L, SEEK_END);

  //go to the beginning of the file
  lseek(*fd, 0L, SEEK_SET);

  //skip the first line
  lseek(*fd, SARMAG, SEEK_CUR);
  
  //start seeking through the file reading file names
  while (fd_size > file_pos) {
    //initialize the delete flag
    is_delete = 0;

    //read the first file name into a char array
    read(*fd, file_name, 16);
    i = 0;
    while (i < sizeof(file_name)) {
      if (file_name[i] == '/') {
	file_name[i] = '\0';
	break;
      };
      i ++;
    }
    
    //check to see if this file is to be deleted
    i = 3;
    while (args[i] != '\0') {
       if (strcmp(args[i], file_name) == 0)
    	is_delete = 1;
       i++;
    }

    //if it is not deleted, append the file
    if (!is_delete) {
      append_one_file("tmp~.a", file_name);
    }

    //get the file size
    lseek(*fd, 48 - 16, SEEK_CUR);  
    read(*fd, file_size, 10);
  
    //seek to the next file header
    if (atoi(file_size)%2 == 0)
      file_pos = lseek(*fd, atoi(file_size) + 2, SEEK_CUR);
    else
      file_pos = lseek(*fd, atoi(file_size) + 3, SEEK_CUR);    
  }

  unlink(args[2]);
  rename("tmp~.a", args[2]);
}

void append_all(int *fd, char *args[]) {
  DIR *d;
  int fs, r, w, j, count;
  int is_text;
  char *dir_name = ".";
  char entry_name[16];
  char ch;
  char file_name[16];
  char file_epoch[12];
  char file_owner[6];
  char file_group[6];
  char file_mode[8];
  char file_size[10];
  struct stat s;
  struct tm tm;

  d = opendir(dir_name);

  if (!d) {
    perror("Cannot open directory");
    exit (EXIT_FAILURE);
  }

  while (1) {
    struct dirent *entry;

    entry = readdir(d);
    is_text = 1; //start by assuming this is a text file

    if (!entry) 
      break;

    fs = open(entry->d_name, O_RDONLY);

    if (strcmp(args[2], entry->d_name) == 0)
      continue;

    if (fs == -1) {
      perror("Can't open source file");
      exit(EXIT_FAILURE);
    }
    
    while ((r = read(fs, &ch, sizeof(ch))) != 0) {
      if (r < 0) {
		is_text = 0;
		break;
      }
      if (ch < 0) {
		is_text = 0;
		break;
      }
    }

    if (!is_text) 
      continue;

    //start appending the files
    fs = open(entry->d_name, O_RDONLY);
    if (fs == -1) {
      perror("Can't open source file");
      exit(EXIT_FAILURE);
    }
    
    //collect the file stats
    stat(entry->d_name, &s);

    memcpy(entry_name, entry->d_name, 16);

    //get the file name
    j = 0;
    while (j < sizeof(entry_name)-1) {
      if (entry_name[j] == 0)
	break;
      file_name[j] = entry_name[j];
      j++;
    }
    file_name[j++] = '/';
    while (j < sizeof(file_name)) {
      file_name[j] = ' ';
      j++;
    }
    
    //get the file epoch time
    memset(&file_epoch[0], 0, sizeof(file_epoch));
    tm = *localtime(&s.st_mtime);
    strftime(file_epoch, sizeof(file_epoch), "%s", &tm);
    j = 0;
    while (j < 12) {
      if (file_epoch[j] == 0)
	file_epoch[j] = ' ';
      j++;
    }

    //get the file owner
    memset(&file_owner[0], 0, sizeof(file_owner));
    snprintf(file_owner, sizeof(file_owner), "%d", (int) s.st_uid);
    j = 0;
    while (j < sizeof(file_owner)) {
      if (file_owner[j] == 0)
	file_owner[j] = ' ';
      j++;
    }

    //get the file group
    memset(&file_group[0], 0, sizeof(file_group));
    snprintf(file_group, sizeof(file_group), "%d", (int) s.st_gid);
    j = 0;
    while (j < sizeof(file_group)) {
      if (file_group[j] == 0)
	file_group[j] = ' ';
      j++;
    }

    //get the file permissions
    memset(&file_mode[0], 0, sizeof(file_mode));
    snprintf(file_mode, sizeof(file_mode), "%o", s.st_mode);
    j = 0;
    while (j < sizeof(file_mode)) {
      if (file_mode[j] == 0)
	file_mode[j] = ' ';
      j++;
    }

    //get the file size
    memset(&file_size[0], 0, sizeof(file_size));
    snprintf(file_size, sizeof(file_size), "%d", (int) s.st_size);
    j = 0;
    while (j < sizeof(file_size)) {
      if (file_size[j] == 0)
	file_size[j] = ' ';
      j++;
    }
    
    //write the header to the archive
    write(*fd, file_name, sizeof(file_name));
    write(*fd, file_epoch, sizeof(file_epoch));
    write(*fd, file_owner, sizeof(file_owner));
    write(*fd, file_group, sizeof(file_group));
    write(*fd, file_mode, sizeof(file_mode));
    write(*fd, file_size, sizeof(file_size));
    write(*fd, ARFMAG, sizeof(ARFMAG)-1);
    
    //write the file contents to the archive
    count = 0;
    while ((r = read(fs, &ch, sizeof(ch))) != 0) {
      if (r < 0) {
        perror("Error while reading the source file");
        exit(EXIT_FAILURE);
      }
      w = write(*fd, &ch, sizeof(ch));
      if (w < 0) {
      	perror("Error while writing to archive");
      	exit(EXIT_FAILURE);
      }
      count++;
    }

    //add a new line for odd character files
    if (count%2 == 1) {
      write(*fd, "\n", 1);
    }
    
    //close the source file, and move to the next argument
    close(fs);

    //just try first file for now
    //break;
  }

  if (closedir(d)) {
    perror("Cannot close directory");
    exit (EXIT_FAILURE);
  }
}

void append_files(int *fd, char *args[]) {
  int i, j;
  int r, w;
  int count;
  int fs;
  char ch;
  char file_name[16];
  char file_epoch[12];
  char file_owner[6];
  char file_group[6];
  char file_mode[8];
  char file_size[10];
  struct stat s;
  struct tm tm;

  //write the file's magic string
  write(*fd, ARMAG, SARMAG);

  //start with the source file names
  i = 3;

  //loop through the source files & write to archive
  while (args[i] != '\0') {
    fs = open(args[i], O_RDONLY);
    if (fs == -1) {
      perror("Can't open source file");
      exit(EXIT_FAILURE);
    }

    //collect the file stats
    stat(args[i], &s);

    //get the file name
    j = 0;
    while (j < sizeof(args[i])-1) {
      file_name[j] = args[i][j];
      j++;
    }
    file_name[j++] = '/';
    while (j < 16) {
      file_name[j] = ' ';
      j++;
    }

    //get the file epoch time
    memset(&file_epoch[0], 0, sizeof(file_epoch));
    tm = *localtime(&s.st_mtime);
    strftime(file_epoch, sizeof(file_epoch), "%s", &tm);
    j = 0;
    while (j < 12) {
      if (file_epoch[j] == 0)
	file_epoch[j] = ' ';
      j++;
    }

    //get the file owner
    memset(&file_owner[0], 0, sizeof(file_owner));
    snprintf(file_owner, sizeof(file_owner), "%d", (int) s.st_uid);
    j = 0;
    while (j < sizeof(file_owner)) {
      if (file_owner[j] == 0)
	file_owner[j] = ' ';
      j++;
    }

    //get the file group
    memset(&file_group[0], 0, sizeof(file_group));
    snprintf(file_group, sizeof(file_group), "%d", (int) s.st_gid);
    j = 0;
    while (j < sizeof(file_group)) {
      if (file_group[j] == 0)
	file_group[j] = ' ';
      j++;
    }

    //get the file permissions
    memset(&file_mode[0], 0, sizeof(file_mode));
    snprintf(file_mode, sizeof(file_mode), "%o", s.st_mode);
    j = 0;
    while (j < sizeof(file_mode)) {
      if (file_mode[j] == 0)
	file_mode[j] = ' ';
      j++;
    }

    //get the file size
    memset(&file_size[0], 0, sizeof(file_size));
    snprintf(file_size, sizeof(file_size), "%d", (int) s.st_size);
    j = 0;
    while (j < sizeof(file_size)) {
      if (file_size[j] == 0)
	file_size[j] = ' ';
      j++;
    }

    //write the header to the archive
    write(*fd, file_name, sizeof(file_name));
    write(*fd, file_epoch, sizeof(file_epoch));
    write(*fd, file_owner, sizeof(file_owner));
    write(*fd, file_group, sizeof(file_group));
    write(*fd, file_mode, sizeof(file_mode));
    write(*fd, file_size, sizeof(file_size));
    write(*fd, ARFMAG, sizeof(ARFMAG)-1);

    //write the file contents to the archive
    count = 0;
    while ((r = read(fs, &ch, sizeof(ch))) != 0) {
      if (r < 0) {
        perror("Error while reading the source file");
        exit(EXIT_FAILURE);
      }
      w = write(*fd, &ch, sizeof(ch));
      if (w < 0) {
      	perror("Error while writing to archive");
      	exit(EXIT_FAILURE);
      }
      count++;
    }

    //add a new line for odd character files
    if (count%2 == 1) {
      write(*fd, "\n", 1);
    }

    //close the source file, and move to the next argument
    close(fs);
    i++;
  }
}

void print_toc(int *fd) {  
  int fd_size;
  char file_name[16];
  char file_size[10];
  int file_pos;
  int i;

  if (*fd == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  fd_size = lseek(*fd, 0L, SEEK_END);

  //go to the beginning of the file
  lseek(*fd, 0L, SEEK_SET);

  //skip the first line
  lseek(*fd, SARMAG, SEEK_CUR);
  
  while (fd_size > file_pos) {
    read(*fd, file_name, 16);

    i = 0;
    while (i < sizeof(file_name)) {
      if (file_name[i] == '/') {
	file_name[i] = '\0';
	break;
      };
      i ++;
    }
  
    printf("%s\n", file_name);

    lseek(*fd, 48 - 16, SEEK_CUR);
  
    read(*fd, file_size, 10);
  
    if (atoi(file_size)%2 == 0)
      file_pos = lseek(*fd, atoi(file_size) + 2, SEEK_CUR);
    else
      file_pos = lseek(*fd, atoi(file_size) + 3, SEEK_CUR);    
  }
}

void print_tocv(int *fd) {  
  int fd_size;
  char file_header[60];
  char file_size[11];
  char file_name[17];
  char file_raw_time[13];
  time_t tt;
  struct tm tm;
  char file_time[80];
  char file_owner[7];
  char file_group[7];
  char file_raw_mode[4];
  char file_mode[10];
  int file_pos = 0;
  int i;

  if (*fd == -1) {
    perror("Can't open archive file");
    exit(EXIT_FAILURE);
  }

  //get the file descriptor size
  fd_size = lseek(*fd, 0L, SEEK_END);

  //go to the beginning of the file
  lseek(*fd, 0L, SEEK_SET);

  //skip the first line
  lseek(*fd, SARMAG, SEEK_CUR);

  //loop through the file, reading all the headers
  while (fd_size > file_pos) {
    read(*fd, file_header, 59);

    //get the file name, then strip off the trailing /
    memcpy(file_name, &file_header[0], 16); 
    i = 0;
    while (i < sizeof(file_name)) {
      if (file_name[i] == '/') {
	    file_name[i] = '\0';
	    break;
      };
      i ++;
    }

    //get the epoch time, then format it 
    memcpy(file_raw_time, &file_header[16], 12);
    file_raw_time[12] = '\0';
    tt = atoi(file_raw_time);
    tm = *localtime(&tt);
    strftime(file_time, sizeof(file_time), "%b %d %H:%M %Y", &tm); 

    //get the file size
    memcpy(file_size, &file_header[48], 10);

    //get the file owner & trim off the trailing spaces
    memcpy(file_owner, &file_header[28], 6);  
    i = 0;
    while (i < sizeof(file_owner)) {
      if (file_owner[i] == ' ') {
	file_owner[i] = '\0';
	break;
      };
      i ++;
    }  

    //get the file group & trim off the trailing spaces
    memcpy(file_group, &file_header[34], 6);  
    i = 0;
    while (i < sizeof(file_group)) {
      if (file_group[i] == ' ') {
	file_group[i] = '\0';
	break;
      };
      i ++;
    }  

    //get the file raw mode
    memcpy(file_raw_mode, &file_header[43], 3);

    //translate numeric to file permission symbols
    i = 0;
    file_mode[0] = '\0';
    while (i < sizeof(file_raw_mode)) {
      switch (file_raw_mode[i]) {
      case 48: //0
        strcat(file_mode, "---");
        break;
      case 49: //1
        strcat(file_mode, "--x");
        break;
      case 50: //2
        strcat(file_mode, "-w-");
        break;
      case 51: //3
        strcat(file_mode, "-wx");
        break;
      case 52: //4
        strcat(file_mode, "r--");
        break;
      case 53: //5
        strcat(file_mode, "r-x");
        break;
      case 54: //6
        strcat(file_mode, "rw-");
        break;
      case 55: //7
        strcat(file_mode, "rwx");
        break;
      }
      i ++;
    }  

    //print the verbose TOC
    printf("%s %s/%s %*d %s %s\n", file_mode, file_owner, file_group, 6, atoi(file_size), file_time, file_name);
 
    //use the file size to jump to the next file header
    if (atoi(file_size)%2 == 0)
      file_pos = lseek(*fd, atoi(file_size) + 1, SEEK_CUR);
    else
      file_pos = lseek(*fd, atoi(file_size) + 2, SEEK_CUR);  
  }
}

int main(int argc, char *argv[])
{
  char c;
  int fd;

  while ((c = getopt (argc, argv, "q:x:t:v:d:A:w:")) != -1) {
    switch (c) {
    case 'q': 
      fd = open(optarg, O_RDWR | O_CREAT, 0666);
      append_files(&fd, argv);
      break;
    case 'x':
      fd = open(optarg, O_RDONLY);
      extract_files(&fd, argv);
      break;
    case 't':
      fd = open(optarg, O_RDONLY);
      print_toc(&fd);
      break;
    case 'v':
      fd = open(optarg, O_RDONLY);
      print_tocv(&fd);
      break;
    case 'd':
      open("tmp~.a", O_RDWR | O_CREAT, 0666);
      fd = open(optarg, O_RDWR | O_CREAT, 0666);
      delete_files(&fd, argv);
      break;
    case 'A':
      fd = open(optarg, O_RDWR | O_CREAT, 0666);
      append_all(&fd, argv);
      break;
    }
  }
  
  close(fd);

  return 0;
}