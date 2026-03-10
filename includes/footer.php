<style>
    /* Footer Styling */
    .footer {
        width: 100%;
        background: #343a40;
        color: white;
        text-align: center;
        padding: 15px 0;
        font-size: 16px;
        font-weight: 500;
        position: fixed;
        bottom: 0;
        left: 0;
        box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    /* Text Effect */
    .footer p {
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.5px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer {
            font-size: 14px;
            padding: 12px 0;
        }
    }
</style>

<div class="footer">
    <p>&copy; <?php echo date("Y"); ?> Jewellery Shop. All Rights Reserved.</p>
</div>

</body>

</html>
