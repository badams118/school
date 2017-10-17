#!/usr/bin/python

#########################################################################################
# The Sieve of Eratosthenes identifies all prime numbers up to a given number n as
# follows:
#   1.  Write down the numbers 1, 2, 3, ..., n. We will eliminate composites by
#       marking them. Initially all numbers are unmarked.
#   2.  Mark the number 1 as special (it is neither prime nor composite).
#   3.  Set k = 1. Until k exceeds or equals the square root of n do:
#       1.  Find the first number in the list greater than k that has not been
#           identified as composite. (The very first number so found is 2.) Call it
#           m. Mark the numbers
#           2 x m, 3 x m, 4 x m, ...
#           as composite. (Thus in the first run we mark all even numbers greater
#           than 2. In the second run we mark all multiples of 3 greater than 3.)
#       2.  m is a prime number. Put it on your list.
#       3.  Set k = m and repeat.
#       4.  Put the remaining unmarked numbers in the sequence on your list of prime
#           numbers.
#
# This Python function returns the number of primes less than a given value, as well as
# the values of all the primes. This function takes a parameter indicating the value for
# which smaller prime numbers are to be generated.
#########################################################################################

import sys
import math

def primes_func(arg):
    n = int(arg)-1
    seq = {1: 'special'}

    for i in range(n-1):
        seq[i+2] = ''
    
    k = 1
    while (k <= math.sqrt(n)):
        m = k
        j = k+1
        while (m == k and j <= len(seq)):
            if seq[j] != 'composite':
                m = j
            else: 
                j = j+1
        
        l = 2
        while (l*m <= len(seq)):
            seq[l*m] = 'composite'
            l = l+1
            
        seq[m] = 'prime'    

        k = m

    for i in range(n):
        if seq[i+1] == '':
            seq[i+1] = 'prime'

    return seq

primes_seq = primes_func(sys.argv[1])

count = 0
primes = ''

for i in range(len(primes_seq)):
    if primes_seq[i+1] == 'prime':
        count += 1
        primes += ' ' + str(i+1) 

print "Number of primes found: %d" % count
print "Prime numbers below %s:" % (sys.argv[1]) + primes