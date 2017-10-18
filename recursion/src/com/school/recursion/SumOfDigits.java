package com.school.recursion;

import javax.swing.*;
import java.awt.event.*;

public class SumOfDigits {
    private JFrame     window;
    private JLabel     prompt, result;
    private JTextField entry;
    private JButton    calc;
    private int        integer, digiSum;

    /**
     * Constructor for the class SumOfDigits.
     * <p>
     * This class sums the decimal digits of a given integer. 
     * 
     * @param  win  the user interface window
     */
    public SumOfDigits(JFrame win)
    {
        window = win;
        prompt = new JLabel("Enter a positive integer to sum:");
        entry  = new JTextField();
        calc   = new JButton("Calculate");
        result = new JLabel();

        window.getContentPane().removeAll();
        
        prompt.setVerticalAlignment(JLabel.TOP);
        prompt.setSize(400, 100);
        prompt.setLocation(10, 5);
        
        window.add(prompt);
        
        entry.setSize(50, 20);
        entry.setLocation(190, 5);
        
        window.add(entry);
        
        calc.setSize(100, 25);
        calc.setLocation(75, 30);
        calc.addActionListener(calcAction());
        
        window.add(calc);
        
        window.repaint();
    }
    
    private ActionListener calcAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                try {                    
                    integer = Integer.parseInt(entry.getText());
                    
                    if (integer < 0) 
                        throw new NumberFormatException();
                    
                    digiSum = 0;
                    
                    calcSum(integer);
                    
                    addResult("The sum of the digits of " + integer + " is " + 
                                digiSum + ".");
                } catch(NumberFormatException e) {
                    addResult("Invalid entry.  Try again.");
                }
            }
        };
        
        return action;
    }
    
    private void addResult(String s)
    {
        window.remove(result);
        
        result.setVerticalAlignment(JLabel.TOP);
        result.setSize(400, 100);
        result.setLocation(10, 65);
        result.setText(s);
        
        window.add(result);
        
        window.repaint();
    }
    
    private void calcSum(int i)
    {
        digiSum += i % 10;
        
        if (i > 9)
            calcSum(i / 10);
    }
}