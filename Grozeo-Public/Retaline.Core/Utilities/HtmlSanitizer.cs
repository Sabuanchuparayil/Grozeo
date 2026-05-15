using System.Text.RegularExpressions;

namespace Retaline.Core.Utilities
{
    public static class HtmlSanitizer
    {
        private static readonly Regex ScriptTagRegex = new(
            @"<script[^>]*>[\s\S]*?</script>",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex EventHandlerRegex = new(
            @"\s+on\w+\s*=\s*(?:""[^""]*""|'[^']*'|[^\s>]+)",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex JavascriptUrlRegex = new(
            @"(href|src|action)\s*=\s*[""']?\s*javascript:",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex StyleExpressionRegex = new(
            @"expression\s*\(",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex DataUrlRegex = new(
            @"(href|src)\s*=\s*[""']?\s*data:",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex IframeRegex = new(
            @"<iframe[^>]*>[\s\S]*?</iframe>",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex ObjectEmbedRegex = new(
            @"<(object|embed|applet)[^>]*>[\s\S]*?</(object|embed|applet)>",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        private static readonly Regex FormRegex = new(
            @"<form[^>]*>[\s\S]*?</form>",
            RegexOptions.IgnoreCase | RegexOptions.Compiled);

        public static string Sanitize(string html)
        {
            if (string.IsNullOrEmpty(html))
                return html;

            html = ScriptTagRegex.Replace(html, string.Empty);
            html = IframeRegex.Replace(html, string.Empty);
            html = ObjectEmbedRegex.Replace(html, string.Empty);
            html = FormRegex.Replace(html, string.Empty);
            html = EventHandlerRegex.Replace(html, string.Empty);
            html = JavascriptUrlRegex.Replace(html, "$1=\"\"");
            html = DataUrlRegex.Replace(html, "$1=\"\"");
            html = StyleExpressionRegex.Replace(html, "blocked(");

            return html.Trim();
        }
    }
}
