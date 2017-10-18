package com.school.recursion;

import javax.swing.*;
import java.awt.event.*;

public class Power {
    private JFrame     window;
    private JLabel     baseLabel, expLabel, resultLabel;
    private JTextField baseField, expField;
    private JButton    calc;
    private int        base, exponent, count, product;

    /**
     * Constructor for the class Power.
     * <p>
     * This class displays the result of raising a given base integer to a given
     * exponent. 
     * 
     * @param  win  the user interface window
     */
    public Power(JFrame win)
    {
        window      = win;
        baseLabel   = new JLabel("Enter the base:");
        expLabel    = new JLabel("Enter the exponent:");
        baseField   = new JTextField();
        expField    = new JTextField();
        calc        = new JButton("Calculate");
        resultLabel = new JLabel();
        
        window.getContentPane().removeAll();

        baseLabel.setVerticalAlignment(JLabel.TOP);
        baseLabel.setSize(200, 100);
        baseLabel.setLocation(10, 5);
        
        window.add(baseLabel);
        
        baseField.setSize(50, 20);
        baseField.setLocation(100, 5);
        
        window.add(baseField);
        
        expLabel.setVerticalAlignment(JLabel.TOP);
        expLabel.setSize(200, 100);
        expLabel.setLocation(160, 5);
        
        window.add(expLabel);
        
        expField.setSize(50, 20);
        expField.setLocation(275, 5);
        
        window.add(expField);
        
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
                    base     = Integer.parseInt(baseField.getText());
                    exponent = Integer.parseInt(expField.getText());
                    
                    if (exponent < 0)
                        throw new NumberFormatException();
                    
                    count    = exponent;
                    product  = 1;
                    
                    calcPower();
                    
                    addResult(base + " to the power of " + exponent + " is " + 
                                product);
                } catch(NumberFormatException e) {
                    addResult("Invalid entry.  Try again.");
                }
            }
        };
        
        return action;
    }
    
    private void addResult(String s)
    {
        window.remove(resultLabel);
        
        resultLabel.setVerticalAlignment(JLabel.TOP);
        resultLabel.setSize(400, 100);
        resultLabel.setLocation(10, 65);
        resultLabel.setText(s);
        
        window.add(resultLabel);
        
        window.repaint();
    }
    
    private void calcPower()
    {     
        if (count > 0) {
            count --;
            product *= base;
            calcPower();
        }
    }
}