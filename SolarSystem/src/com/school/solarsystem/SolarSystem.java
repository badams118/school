package com.school.solarsystem;

import java.awt.Color;

/**
 * This class a solar system of three planets orbiting a star.
 * 
 * @author Brady Adams 
 * CS 1410 - 003
 * Spring 2007
 * Project 4
 */
public class SolarSystem extends ThreeButtons
{
    // instance variables
    private ThreeButtonFrame win;
    private Star             star;
    private Planet           planet1;
    private Planet           planet2;
    private Planet           planet3;

    /**
     * Constructor for objects of class Driver
     */
    public SolarSystem()
    {
        // initialize instance variables
        star    = new Star();
        planet1 = new Planet (40, 90, 520, 320);
        planet2 = new Planet(75, 125, 450, 250);
        planet3 = new Planet(110, 160, 380, 180);
        
        // make the window
        makeWindow();
     
        // add the Planets to the window
        planet1.addTo(win);
        planet2.addTo(win);
        planet3.addTo(win);
        
        // add the star to the window
        star.addTo(win);
        
        // repaint the window
        win.repaint();
    }

    /**
     * post: The window for the solar system has been created
     */
    private void makeWindow()
    {
        win = new ThreeButtonFrame("3 Button Window");
        win.getContentPane().setLayout(null);
        win.getContentPane().setBackground(Color.darkGray);
    }
    
    /**
     * pre:  The solar system has been created
     * post: The planets have all moved on increment based on their speed 
     *       and direction
     */
    public void midAction()
    {           
        // move the planets
        planet1.move();
        planet2.move();
        planet3.move();
        
        //repaint the window
        win.repaint();
    }
    
    /**
     * pre:  The solar system has been created
     * post: The planets have all reversed direction & color
     */
    public void leftAction()
    {
        // reverse the planet's direction & color
        planet1.reverseDirection();
        planet2.reverseDirection();
        planet3.reverseDirection();
        
        // repaint the window
        win.repaint();
    }
       
    /**
     * pre:  The solar system has been created
     * post: The planets randomly switched orbits
     */
    public void rightAction()
    {   
        // randomly decide what order to swap the planets to
        int rand = Math.round( (float) (Math.random() * 4) );
                   
                 // initial order: 1 2 3
        switch (rand) {
            case 0:  // new order: 1 3 2
                     swap(planet2, planet3);
                     break;
            case 1:  // new order: 2 1 3
                     swap(planet1, planet2);
                     break;
            case 2:  // new order: 2 3 1
                     swap(planet1, planet2);
                     swap(planet2, planet3);
                     break;
            case 3:  // new order: 3 1 2
                     swap(planet1, planet2);
                     swap(planet1, planet3);
                     break;
            case 4:  // new order: 3 2 1
                     swap(planet1, planet3);
                     break;
            default: System.out.println("Trying to make swap planets in " + 
                                        "case " + rand);
                     System.out.println("Something went terribly wrong");
                     break;
        }
       
        // repaint the window
        win.repaint();
    }
    
    /**
     * pre:  The solar system has been created
     * post: The two planets passed are swapped
     */
    private void swap(Planet p1, Planet p2)
    {
        // create a temporary planet
        Planet p0 = new Planet(0, 0, 0, 0);
        
        // swap the planets
        p0 = p1.swap(p0);
        p1 = p2.swap(p1);
        p2 = p0.swap(p2);
    }
}