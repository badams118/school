package com.school.solarsystem;

import java.awt.*;
import javax.swing.*;

/**
 * This class creates a planet with its orbit on a container.
 * 
 * @author Brady Adams 
 * CS 1410 - 003
 * Spring 2007
 * Project 4
 */
public class Planet
{
    // instance variables 
    private Oval      planet;
    private int       angle;
    private double    angleRadian;
    private int       speed;
    private Orbit     orbit;
    private Container planetOnOrbit;
    private final     Color positive = Color.red;
    private final     Color negative = Color.blue;

    /**
     * Constructor for objects of class Planet
     */
    public Planet(int x, int y, int w, int h)
    {
        // initialize instance variables
        planet        = new Oval(0, 0, 0, 0);
        planetOnOrbit = new Container();
        
        // set the size of the container to fit the orbit and the planet
        planetOnOrbit.setBounds(x - 17, y - 17, w + 34, h + 34);
        
        // create the orbit
        makeOrbit(w, h);
        
        // add the orbit to the container
        orbit.addTo(planetOnOrbit);
        
        // set up the initial values for the planet
        makeMass();
        setSpeed();
        setBodyColor();
        setBodyLocation();
        
        // add the planet to the container
        planetOnOrbit.add(planet, 0);
    }
    
    /**
     * post: An orbit is created of specified width & height
     */
    private void makeOrbit(int w, int h)
    {
        orbit = new Orbit(w, h);
    }
    
    /**
     * pre:  The planet has been created
     * post: The planet has a randomly generated mass
     */
    private void makeMass()
    {
        int mass = Math.round( (float) Math.random() * (34 - 10) ) + 10;
        planet.setSize(mass, mass);
    }
    
    /**
     * pre:  The planet has been created
     * post: The planet has a randomly generated speed
     */
    private void setSpeed()
    {
        if (Math.random() < .5)
            speed = Math.round( (float) Math.random() * (11 - 1) ) + 1;
        else
        {
            speed = Math.round( (float) Math.random() * (11 - 1) ) + 1;
            speed = speed * -1;
        }
    }
    
    /**
     * pre:  The planet has been created
     * post: The planet has a red color if it is moving clockwise, and a 
     *       blue color if it is moving counter clockwise
     */
    private void setBodyColor()
    {
        if (speed > 0)
            planet.setBackground(positive);
        else
            planet.setBackground(negative);
    }
    
    /**
     * pre:  The planet has been created
     * post: The planet has a randomly generated location on the orbit
     */
    private void setBodyLocation()
    {
        // randomly generate the angle
        angle       = Math.round( (float) Math.random() * (359) );
        
        // convert the angle from degrees to radians
        angleRadian = convertToRadians(angle);
        
        // set the location of the planet based on the angle
        planet.setLocation(calculateOrbitX(), calculateOrbitY());
    }
    
    /**
     * pre:  The planet has been created
     * post: The planet has been added to the window
     */
    public void addTo(JFrame win)
    {
        win.getContentPane().add(planetOnOrbit, 0);
    }
    
    /**
     * pre:  The planet has been created
     * post: Sets the new location of the planet based on the speed & 
     *       direction
     */
    public void move()
    {
        // calculate the new angle based on the speed
        angle = angle - speed;
        
        // convert the angle from degrees to radians
        angleRadian = convertToRadians(angle);
        
        // set the new location of the planet
        planet.setLocation(calculateOrbitX(), calculateOrbitY());
    }
    
    /**
     * pre:  The solar system has been created
     * post: The direction & color of the planets have been reversed
     */
    public void reverseDirection()
    {
        // reverse the speed
        speed = speed * -1;
        
        // reverse the color
        setBodyColor();
    }
    
    /**
     * pre:  The solar system has been created
     * post: Sets the size, speed, direction, and location of the planet 
     *       being passed to the size, speed, direction, and location of 
     *       this planet
     */
    public Planet swap(Planet p)
    {
        // first, calculate the offset of the planet based on the new size
        int planetOffset = Math.round( (float) ((this.planet.getWidth() - 
                                                p.planet.getHeight())/2) );
        
        // set the passed planet's values to the current planet's values
        p.planet.setSize(this.planet.getHeight(), this.planet.getWidth());
        p.speed = this.speed;
        p.setBodyColor();
        p.planet.setLocation(p.planet.getX() - planetOffset, p.planet.getY() 
                                             - planetOffset);
        p.planetOnOrbit.add(p.planet, 0);
        
        // return the passed planet with the new values
        return p;
    }
    
    /**
     * post: Returns new x position
     */
    private int calculateOrbitX()
    {
        // local variables
        double xOffset;
        double x;
        
        // calculate new x position
        xOffset = orbit.getWidth()/2 * Math.sin(angleRadian);
        x       = orbit.getX() + orbit.getWidth()/2 + xOffset -
                  planet.getWidth()/2;
        
        // return x position
        return Math.round((float) x);
    }
    
    /**
     * post: Returns new y position
     */
    private int calculateOrbitY()
    {
        // local variables
        double yOffset;
        double y;
        
        // calculate new y position
        yOffset = orbit.getHeight()/2 * Math.cos(angleRadian);
        y       = orbit.getY() + orbit.getHeight()/2 - yOffset - 
                  planet.getHeight()/2;
        
        // return y position
        return Math.round((float) y);
    }
    
    /**
     * post: Returns the radian value of the degree parameter passed
     */
    private double convertToRadians(int degrees)
    {
        // local variable
        final int degreesInCircle = 360;
        
        // convert the angle in degrees to radians
        double radians = (degrees * 2 * Math.PI)/degreesInCircle;
        
        // return the radian
        return radians;
    }
}