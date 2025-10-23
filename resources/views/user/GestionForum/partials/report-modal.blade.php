<div id="reportModal" class="report-modal">
    <div class="report-modal-content">
        <div class="report-modal-header">
            <h2>Report</h2>
            <button class="close-modal" onclick="closeReportModal()">&times;</button>
        </div>
        
        <div class="report-modal-body">
            <h3>Why are you reporting this post?</h3>
            <p class="report-subtitle">If someone is in immediate danger, get help before reporting to us.</p>
            
            <div class="report-reasons">
                <div class="reason-category">
                    <h4>Inappropriate Content</h4>
                    <div class="reason-options">
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="spam">
                            <span class="reason-text">
                                <strong>Spam</strong>
                                <span class="reason-description">Unsolicited advertising or commercial content</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="harassment">
                            <span class="reason-text">
                                <strong>Harassment or bullying</strong>
                                <span class="reason-description">Threatening or intimidating content</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="hate_speech">
                            <span class="reason-text">
                                <strong>Hate speech</strong>
                                <span class="reason-description">Attacks based on race, ethnicity, religion, etc.</span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="reason-category">
                    <h4>Safety Issues</h4>
                    <div class="reason-options">
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="violence">
                            <span class="reason-text">
                                <strong>Violence or dangerous content</strong>
                                <span class="reason-description">Inciting violence or dangerous content</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="self_harm">
                            <span class="reason-text">
                                <strong>Suicide or self-harm</strong>
                                <span class="reason-description">Content related to suicide or self-harm</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="scam">
                            <span class="reason-text">
                                <strong>Scam or fraud</strong>
                                <span class="reason-description">Attempted scam or identity theft</span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="reason-category">
                    <h4>Other Issues</h4>
                    <div class="reason-options">
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="intellectual_property">
                            <span class="reason-text">
                                <strong>Intellectual property</strong>
                                <span class="reason-description">Copyright or trademark infringement</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="false_info">
                            <span class="reason-text">
                                <strong>False information</strong>
                                <span class="reason-description">Clearly false or misleading information</span>
                            </span>
                        </label>
                        
                        <label class="reason-option">
                            <input type="radio" name="reportReason" value="other">
                            <span class="reason-text">
                                <strong>Other</strong>
                                <span class="reason-description">Other reason not listed above</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Additional information textarea -->
            <div class="additional-info" id="additionalInfo" style="display: none;">
                <label for="reportDetails" class="details-label">Describe the problem (optional)</label>
                <textarea 
                    id="reportDetails" 
                    class="report-details" 
                    placeholder="Provide more details to help us understand the issue..."
                    rows="4"
                ></textarea>
            </div>
        </div>
        
        <div class="report-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeReportModal()">Cancel</button>
            <button type="button" class="btn btn-primary" id="submitReportBtn" onclick="submitReport()" disabled>
                <span class="btn-text">Submit Report</span>
                <div class="btn-loading" style="display: none;">
                    <div class="spinner"></div>
                    <span>Submitting...</span>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Report Confirmation -->
<div id="reportConfirmation" class="report-confirmation">
    <div class="confirmation-content">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>Thank you for letting us know</h3>
        <p>We use your feedback to help our systems recognize inappropriate content.</p>
        
        <div class="confirmation-actions">
            <button type="button" class="btn btn-primary" onclick="closeConfirmation()">
                Done
            </button>
        </div>
    </div>
</div>